import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const rootElement = document.documentElement;

const resolveTheme = () => {
    try {
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme === 'dark' || storedTheme === 'light') {
            return storedTheme;
        }
    } catch (_) {
        // ignore storage errors
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

const applyTheme = (theme) => {
    rootElement.classList.toggle('dark', theme === 'dark');
    rootElement.dataset.theme = theme;

    document.querySelectorAll('[data-theme-label]').forEach((label) => {
        label.textContent = theme === 'dark' ? 'Light' : 'Dark';
    });
};

const escapeHtml = (value) => value
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#39;');

const initCopyButtons = () => {
    document.querySelectorAll('[data-copy-button]').forEach((button) => {
        button.addEventListener('click', async () => {
            const selector = button.getAttribute('data-copy-source');
            const source = selector ? document.querySelector(selector) : null;
            const text = source?.textContent ?? '';

            if (!text) {
                return;
            }

            await navigator.clipboard.writeText(text);

            const original = button.textContent;
            button.textContent = 'Copied';
            setTimeout(() => {
                button.textContent = original;
            }, 1200);
        });
    });
};

const initRunningTimerLabels = () => {
    const labels = document.querySelectorAll('[data-running-timer]');

    if (!labels.length) {
        return;
    }

    const formatDuration = (seconds) => {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = seconds % 60;
        return `${h}h ${String(m).padStart(2, '0')}m ${String(s).padStart(2, '0')}s`;
    };

    const update = () => {
        const now = Date.now();

        labels.forEach((label) => {
            const raw = label.getAttribute('data-running-timer');

            if (!raw) {
                return;
            }

            const start = new Date(raw).getTime();
            const seconds = Math.max(0, Math.floor((now - start) / 1000));
            label.textContent = `seit ${formatDuration(seconds)}`;
        });
    };

    update();
    setInterval(update, 1000);
};

const initMarkdownPreview = () => {
    document.querySelectorAll('[data-markdown-preview]').forEach((wrapper) => {
        const endpoint = wrapper.getAttribute('data-preview-endpoint');
        const input = wrapper.querySelector('[data-markdown-input]');
        const output = wrapper.querySelector('[data-markdown-output]');

        if (!endpoint || !input || !output || !csrfToken) {
            return;
        }

        let timer;

        const render = async () => {
            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ content_markdown: input.value }),
                });

                if (!response.ok) {
                    return;
                }

                const payload = await response.json();
                output.innerHTML = payload.html ?? '';
            } catch (_) {
                // no-op for local preview failures
            }
        };

        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(render, 250);
        });
    });
};

const initGlobalSearch = () => {
    document.querySelectorAll('[data-global-search]').forEach((wrapper) => {
        const input = wrapper.querySelector('[data-search-input]');
        const resultsBox = wrapper.querySelector('[data-search-results]');

        if (!input || !resultsBox) {
            return;
        }

        let timer;

        const hide = () => {
            resultsBox.classList.add('hidden');
            resultsBox.innerHTML = '';
        };

        const renderSection = (title, items, toUrl, description = null) => {
            if (!items.length) {
                return '';
            }

            const links = items.map((item) => {
                const text = escapeHtml(description ? `${item.title} (${item.language})` : (item.title ?? item.name));
                return `<a href="${toUrl(item)}" class="block rounded-lg px-2 py-1.5 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">${text}</a>`;
            }).join('');

            return `<div><p class="px-2 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">${title}</p>${links}</div>`;
        };

        input.addEventListener('input', () => {
            clearTimeout(timer);

            timer = setTimeout(async () => {
                const query = input.value.trim();

                if (query.length < 2) {
                    hide();
                    return;
                }

                const response = await fetch(`/search?q=${encodeURIComponent(query)}`);

                if (!response.ok) {
                    hide();
                    return;
                }

                const payload = await response.json();

                const html = [
                    renderSection('Projekte', payload.projects ?? [], (item) => `/projects/${item.id}`),
                    renderSection('Notizen', payload.notes ?? [], (item) => `/notes/${item.id}`),
                    renderSection('Snippets', payload.snippets ?? [], (item) => `/snippets/${item.id}`, true),
                ].filter(Boolean).join('<div class="my-2 border-t border-slate-100 dark:border-slate-700"></div>');

                if (!html) {
                    hide();
                    return;
                }

                resultsBox.innerHTML = html;
                resultsBox.classList.remove('hidden');
            }, 220);
        });

        document.addEventListener('click', (event) => {
            if (!wrapper.contains(event.target)) {
                hide();
            }
        });
    });
};

const initThemeToggle = () => {
    const toggleButtons = document.querySelectorAll('[data-theme-toggle]');

    if (!toggleButtons.length) {
        return;
    }

    let currentTheme = resolveTheme();
    applyTheme(currentTheme);

    toggleButtons.forEach((button) => {
        button.addEventListener('click', () => {
            currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(currentTheme);

            try {
                localStorage.setItem('theme', currentTheme);
            } catch (_) {
                // ignore storage errors
            }
        });
    });
};

initCopyButtons();
initThemeToggle();
initMarkdownPreview();
initGlobalSearch();
initRunningTimerLabels();
