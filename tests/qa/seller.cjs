// Run against a QA account: QA_BASE_URL, QA_CREDENTIALS_FILE, PLAYWRIGHT_MODULE.
const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

(async () => {
    const credentials = JSON.parse(fs.readFileSync(process.env.QA_CREDENTIALS_FILE, 'utf8'));
    const baseURL = process.env.QA_BASE_URL || 'http://127.0.0.1:8011';
    const browser = await chromium.launch({ headless: true, args: ['--disable-quic'] });
    const context = await browser.newContext({ baseURL, acceptDownloads: true });
    const page = await context.newPage();
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    const check = message => console.log('PASS ' + message);
    try {
        await page.goto('/login');
        await page.locator('[name=email]').fill(credentials.email);
        await page.locator('[name=password]').fill(credentials.password);
        await Promise.all([page.waitForURL(url => url.pathname === '/'), page.locator('button[type=submit]').click()]);
        check('login QA seller');

        await page.goto('/dashboard/profile');
        assert.equal(await page.locator('#contact_email').isEditable(), true);
        await page.locator('#contact_email').fill('qa-contact@example.invalid');
        await Promise.all([page.waitForNavigation(), page.locator('#formSellerProfile button[type=submit]').click()]);
        assert.equal(await page.locator('#contact_email').inputValue(), 'qa-contact@example.invalid');
        check('contact email persists after save and reload');
        await context.request.post('/dashboard/ubah-profil-toko', { form: { store_name: 'QA Dummy Store', contact_email: 'invalid-email' } });
        await page.reload();
        assert.equal(await page.locator('#contact_email').inputValue(), 'qa-contact@example.invalid');
        check('invalid contact email rejected without overwriting profile');
        await page.goto('/creator/profile');
        assert.ok(await page.locator('a[href="mailto:qa-contact@example.invalid"]').count());
        check('public profile uses saved contact email');

        await page.goto('/dashboard/add-asset');
        const title = 'DUMMY QA pending ' + Date.now();
        await page.locator('[name=title]').fill(title);
        const category = await page.locator('#category_id option:not([disabled])').evaluateAll(options => options.find(o => Number(o.value) > 0).value);
        await page.locator('#category_id').selectOption(category);
        await page.locator('#description').fill('Paket dummy untuk pengujian QA, bukan aset asli.');
        await page.locator('#unity_version').selectOption('2022.3 LTS');
        await page.locator('#how_to_use').fill('Paket dummy untuk pengujian unduhan.');
        await page.locator('#pipe_urp').check();
        await page.locator('#price_free').check();
        await page.locator('#imageFileInput').setInputFiles(path.resolve(__dirname, '../../public/assets/img/placeholder.png'));
        await page.locator('#packageFileInput').setInputFiles(path.resolve(__dirname, '../../database/seeders/fixtures/DUMMY-QA-ASSET.zip'));
        await Promise.all([page.waitForURL('**/dashboard/assets'), page.locator('button[type=submit]').click()]);
        const row = page.locator('#assetsTableBody tr').filter({ hasText: title });
        await row.waitFor();
        assert.match(await row.innerText(), /Pending/);
        assert.ok(await page.locator('.alert-success').count());
        await page.locator('#filterStatus').selectOption('Pending');
        assert.equal(await row.count(), 1);
        check('uploaded asset appears in pending table and pending filter');
        await page.goto('/dashboard');
        assert.ok(await page.locator('#recentAssetsBody, #recentAssetsTableBody').count() || await page.getByText(title).count());
        check('seller dashboard renders asset data');

        await page.goto('/catalog/explore');
        const assetLink = await page.locator('a[href^="/asset/detail?id="]').first().getAttribute('href');
        assert.ok(assetLink);
        const menus = [];
        for (const route of ['/catalog/explore', assetLink, '/cart', '/checkout', '/creator/profile', '/dashboard/ubah-profil']) {
            await page.goto(route);
            await page.locator('#navbar-avatar').click();
            assert.equal(await page.locator('#navbar-avatar-menu').isVisible(), true);
            menus.push(await page.locator('#navbar-avatar-menu a').allTextContents());
            await page.keyboard.press('Escape');
            assert.equal(await page.locator('#navbar-avatar-menu').isVisible(), false);
        }
        menus.forEach(menu => assert.deepEqual(menu, menus[0]));
        check('profile menu consistent and clickable on catalog/detail/cart/checkout/profile/edit profile; Escape closes menu');

        await page.goto(assetLink);
        await Promise.all([page.waitForURL('**/cart'), page.locator('form[action="/cart/add"] button').click()]);
        assert.ok(await page.locator('.cart-item-card').count());
        for (const width of [320, 375, 768, 1280]) {
            await page.setViewportSize({ width, height: 900 });
            await page.goto('/cart');
            const boxes = await page.locator('.cart-item-card, .cart-item-thumb, .cart-item-content, .cart-item-actions').evaluateAll(elements => elements.map(e => {
                const box = e.getBoundingClientRect(); return { left: box.left, right: box.right, width: box.width };
            }));
            boxes.forEach(box => assert.ok(box.left >= -1 && box.right <= width + 1, JSON.stringify({ width, box })));
            await page.goto('/catalog/explore');
            const order = await page.evaluate(() => {
                const filter = document.querySelector('.filter-search-card').parentElement;
                const grid = document.querySelector('.col-lg-9');
                return { filter: filter.getBoundingClientRect(), grid: grid.getBoundingClientRect() };
            });
            if (width < 992) assert.ok(order.filter.top < order.grid.top);
            else assert.ok(order.filter.left > order.grid.left);
            if (width === 375) await page.screenshot({ path: '/tmp/indofx-qa-explore-mobile.png', fullPage: true });
        }
        check('cart stays within viewport at 320/375/768/1280; Explore filters above assets on mobile and right on desktop');

        await page.setViewportSize({ width: 375, height: 900 });
        await page.goto('/cart');
        await page.screenshot({ path: '/tmp/indofx-qa-cart-mobile.png', fullPage: true });
        await page.locator('.btn-cart-delete').first().click();
        await page.locator('#deleteConfirmModal.show, .modal.show').waitFor();
        await page.getByRole('button', { name: 'Batal', exact: true }).click();
        await page.locator('.modal.show').waitFor({ state: 'hidden' });
        check('cart delete modal opens and cancel closes it');
        const freeAssetId = Number(process.env.QA_FREE_ASSET_ID);
        assert.ok(freeAssetId > 0, 'Set QA_FREE_ASSET_ID to a published free asset with a dummy package');
        const claimed = await context.request.post('/api/library/add', { data: { id: freeAssetId } });
        assert.equal((await claimed.json()).success, true);
        const download = await context.request.get('/library/download?id=' + freeAssetId);
        assert.equal(download.status(), 200);
        assert.match(download.headers()['content-disposition'], /DUMMY-QA/);
        assert.equal((await download.body()).subarray(0, 2).toString(), 'PK');
        await page.goto('/library/detail?id=' + freeAssetId);
        assert.ok(await page.getByText('Paket dummy untuk pengujian QA. Paket ini bukan aset Unity asli.', { exact: true }).count());
        check('free claim and download returns a real ZIP with visible dummy label');
        const unauthorized = await browser.newContext({ baseURL });
        const guestDownload = await unauthorized.request.get('/library/download?id=' + freeAssetId);
        assert.equal(new URL(guestDownload.url()).pathname, '/login');
        await unauthorized.close();
        check('guest cannot download packages');
        assert.deepEqual(errors, []);
        check('no browser JavaScript errors');
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exitCode = 1; });
