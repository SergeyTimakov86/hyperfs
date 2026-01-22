import Keycloak from 'https://cdn.jsdelivr.net/npm/keycloak-js@26.2.2/+esm';

const keycloak = new Keycloak({
    url: 'http://localhost:8081',
    realm: 'realm-mmorket',
    clientId: 'mmorket-web'
});

await keycloak.init({
    onLoad: 'check-sso',
    pkceMethod: 'S256',
    silentCheckSsoRedirectUri:
        window.location.origin + '/silent-check-sso.html'
}).then(() => {
    document.getElementById('login-overlay').hidden = true;
});

window.keycloak = keycloak;

let loginInProgress = false;

document.addEventListener('click', (e) => {
    const link = e.target.closest('a');
    if (!link) return;

    const href = link.getAttribute('href');
    if (!href?.startsWith('/api/')) return;

    if (keycloak.authenticated || loginInProgress) return;

    e.preventDefault();
    loginInProgress = true;

    showLogin();
});

function showLogin() {
    document.getElementById('login-overlay').hidden = false;
    keycloak.login({ redirectUri: window.location.href });
}

async function apiFetch(url, options = {}) {
    if (!keycloak.authenticated) {
        await keycloak.login({ redirectUri: window.location.href });
        return;
    }

    await keycloak.updateToken(30); // обновить, если скоро истекает

    const headers = new Headers(options.headers || {});
    headers.set('Authorization', `Bearer ${keycloak.token}`);

    return fetch(url, {
        ...options,
        headers
    });
}