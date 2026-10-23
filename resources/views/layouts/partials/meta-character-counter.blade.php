{{-- Global Meta Tag & Meta Description Real-time Character Counter --}}
<style>
    .meta-char-counter-badge {
        font-size: 11.5px !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        transition: all 0.2s ease-in-out !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        user-select: none !important;
    }
    .meta-char-counter-badge.count-empty {
        background-color: #f1f5f9 !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
    }
    .meta-char-counter-badge.count-optimal {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border: 1px solid #a7f3d0 !important;
    }
    .meta-char-counter-badge.count-warning {
        background-color: #fffbeb !important;
        color: #b45309 !important;
        border: 1px solid #fde68a !important;
    }
    .meta-char-counter-badge.count-over {
        background-color: #fef2f2 !important;
        color: #b91c1c !important;
        border: 1px solid #fecaca !important;
    }
</style>

<script>
    (function() {
        function getMetaTargetType(el) {
            const name = (el.getAttribute('name') || '').toLowerCase();
            const id = (el.getAttribute('id') || '').toLowerCase();

            // Meta Tag / Title
            if (name === 'metatag' || name === 'meta_tag' || name === 'meta_title' || id === 'metatag' || id === 'meta_title') {
                return 'tag';
            }
            // Meta Description
            if (name === 'metadescription' || name === 'meta_description' || name === 'meta_discribtion' || id === 'metadescription' || id === 'meta_description') {
                return 'desc';
            }

            // Check parent or previous label text
            const container = el.closest('.mb-3, .mb-4, .mt-4, .col-md-6, .col-12, .form-group') || el.parentElement;
            if (container) {
                const label = container.querySelector('label');
                if (label) {
                    const txt = (label.textContent || '').toLowerCase().trim();
                    if (txt.includes('meta tag') || txt.includes('meta title')) return 'tag';
                    if (txt.includes('meta desc') || txt.includes('meta description') || txt.includes('meta disc')) return 'desc';
                }
            }

            return null;
        }

        function updateCounterBadge(inputEl, badgeEl, type) {
            if (!inputEl || !badgeEl) return;
            const len = (inputEl.value || '').length;
            const countSpan = badgeEl.querySelector('.char-count') || badgeEl;
            
            countSpan.textContent = len;

            // Remove previous count classes
            badgeEl.classList.remove('count-empty', 'count-optimal', 'count-warning', 'count-over', 'badge-light-primary');

            // Guidance limits:
            // Meta Tag / Title: ~50-60 chars optimal, >65 warning/over
            // Meta Description: ~140-160 chars optimal, >165 warning/over
            if (len === 0) {
                badgeEl.classList.add('count-empty');
                badgeEl.innerHTML = '<span class="char-count">0</span> characters';
            } else if (type === 'tag') {
                if (len >= 40 && len <= 65) {
                    badgeEl.classList.add('count-optimal');
                    badgeEl.innerHTML = '<span class="char-count">' + len + '</span> chars (Optimal)';
                } else if (len > 65) {
                    badgeEl.classList.add('count-over');
                    badgeEl.innerHTML = '<span class="char-count">' + len + '</span> chars';
                } else {
                    badgeEl.classList.add('count-warning');
                    badgeEl.innerHTML = '<span class="char-count">' + len + '</span> chars';
                }
            } else if (type === 'desc') {
                if (len >= 130 && len <= 165) {
                    badgeEl.classList.add('count-optimal');
                    badgeEl.innerHTML = '<span class="char-count">' + len + '</span> chars (Optimal)';
                } else if (len > 165) {
                    badgeEl.classList.add('count-over');
                    badgeEl.innerHTML = '<span class="char-count">' + len + '</span> chars';
                } else {
                    badgeEl.classList.add('count-warning');
                    badgeEl.innerHTML = '<span class="char-count">' + len + '</span> chars';
                }
            } else {
                badgeEl.classList.add('count-optimal');
                badgeEl.innerHTML = '<span class="char-count">' + len + '</span> characters';
            }
        }

        function initMetaField(inputEl) {
            if (inputEl.dataset.metaCounterAttached === 'true') return;
            
            const type = getMetaTargetType(inputEl);
            if (!type) return;

            inputEl.dataset.metaCounterAttached = 'true';

            // Find or create badge
            const container = inputEl.closest('.mb-3, .mb-4, .mt-4, .col-md-6, .col-12, .form-group') || inputEl.parentElement;
            let badgeEl = container.querySelector('.meta-char-counter-badge, [id$="-counter"]');

            if (!badgeEl) {
                const label = container.querySelector('label');
                if (label) {
                    // Check if label is inside a d-flex container
                    let headerRow = label.parentElement;
                    if (!headerRow.classList.contains('d-flex')) {
                        headerRow = document.createElement('div');
                        headerRow.className = 'd-flex justify-content-between align-items-center mb-1';
                        label.parentElement.insertBefore(headerRow, label);
                        headerRow.appendChild(label);
                        label.classList.add('mb-0');
                    }
                    badgeEl = document.createElement('span');
                    badgeEl.className = 'meta-char-counter-badge count-empty';
                    headerRow.appendChild(badgeEl);
                } else {
                    // No label found, inject directly before input
                    badgeEl = document.createElement('div');
                    badgeEl.className = 'd-flex justify-content-end mb-1';
                    badgeEl.innerHTML = '<span class="meta-char-counter-badge count-empty">0 characters</span>';
                    inputEl.parentElement.insertBefore(badgeEl, inputEl);
                    badgeEl = badgeEl.querySelector('.meta-char-counter-badge');
                }
            } else {
                if (!badgeEl.classList.contains('meta-char-counter-badge')) {
                    badgeEl.classList.add('meta-char-counter-badge');
                }
            }

            // Sync initial value
            updateCounterBadge(inputEl, badgeEl, type);

            // Bind real-time input events
            const handler = function() {
                updateCounterBadge(inputEl, badgeEl, type);
            };

            inputEl.addEventListener('input', handler);
            inputEl.addEventListener('keyup', handler);
            inputEl.addEventListener('paste', function() {
                setTimeout(handler, 10);
            });
            inputEl.addEventListener('change', handler);
        }

        function initAllMetaCounters() {
            const inputs = document.querySelectorAll('input[type="text"], input:not([type]), textarea');
            inputs.forEach(initMetaField);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAllMetaCounters);
        } else {
            initAllMetaCounters();
        }

        // Also check if dynamic content or tabs open
        setTimeout(initAllMetaCounters, 400);
        setTimeout(initAllMetaCounters, 1200);
    })();
</script>
