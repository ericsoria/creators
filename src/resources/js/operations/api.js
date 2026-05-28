import { apiBaseUrl, authTokenKey } from './config';

export class ApiError extends Error {
    constructor(message, { status, errors } = {}) {
        super(message);
        this.name = 'ApiError';
        this.status = status;
        this.errors = errors || {};
    }
}

let unauthorizedHandler = null;

export function onUnauthorized(handler) {
    unauthorizedHandler = handler;
}

function token() {
    return window.localStorage.getItem(authTokenKey);
}

function url(path, params = {}) {
    const base = apiBaseUrl.replace(/\/$/, '');
    const target = new URL(`${base}/${path.replace(/^\//, '')}`, window.location.origin);

    Object.entries(params).forEach(([key, value]) => {
        if (value !== undefined && value !== null && value !== '') {
            target.searchParams.set(key, value);
        }
    });

    return target.toString();
}

async function parse(response) {
    if (response.status === 204) {
        return null;
    }

    const text = await response.text();
    return text ? JSON.parse(text) : null;
}

export async function request(path, options = {}) {
    const headers = {
        Accept: 'application/json',
        ...(options.body ? { 'Content-Type': 'application/json' } : {}),
        ...options.headers,
    };

    const bearer = token();
    if (bearer) {
        headers.Authorization = `Bearer ${bearer}`;
    }

    const response = await fetch(url(path, options.params), {
        method: options.method || 'GET',
        headers,
        body: options.body ? JSON.stringify(options.body) : undefined,
    });

    const payload = await parse(response);

    if (!response.ok) {
        if (response.status === 401 && unauthorizedHandler) {
            unauthorizedHandler();
        }

        throw new ApiError(payload?.message || 'Request failed.', {
            status: response.status,
            errors: payload?.errors,
        });
    }

    return payload;
}

export const api = {
    login: (credentials) => request('/auth/login', { method: 'POST', body: credentials }),
    logout: () => request('/auth/logout', { method: 'POST' }),
    user: () => request('/user'),
    list: (path, params) => request(path, { params }),
    get: (path, id) => request(`${path}/${id}`),
    create: (path, body) => request(path, { method: 'POST', body }),
    update: (path, id, body) => request(`${path}/${id}`, { method: 'PATCH', body }),
    delete: (path, id) => request(`${path}/${id}`, { method: 'DELETE' }),
    approveProspectAsCreator: (id, body) => request(`/prospects/${id}/approve-as-creator`, { method: 'POST', body }),
    approveProspectAsBrand: (id, body) => request(`/prospects/${id}/approve-as-brand`, { method: 'POST', body }),
};

export function validationMessage(error, field) {
    return error?.errors?.[field]?.[0] || '';
}
