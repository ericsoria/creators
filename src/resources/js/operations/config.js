export const apiBaseUrl =
    import.meta.env.VITE_API_BASE_URL ||
    document.querySelector('meta[name="api-base-url"]')?.content ||
    '/api/v1';

export const authTokenKey = 'creators.operations.token';
