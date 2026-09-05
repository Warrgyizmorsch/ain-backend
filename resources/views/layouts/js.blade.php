<!--end::Scrolltop-->
<!--end::Main-->

<!-- <script>var hostUrl = "{{ asset('assets') }}/";</script> -->

<!--begin::Javascript-->
<!--begin::Global Javascript Bundle (used by all pages)-->
<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->

<!--begin::Page Vendors Javascript (used by this page)-->
<!-- <script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script> -->
<!--end::Page Vendors Javascript-->

<!--begin::Page Custom Javascript (used by this page)-->
<!-- <script src="{{ asset('assets/js/custom/widgets.js') }}"></script> -->
<!-- <script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script> -->
<!-- <script src="{{ asset('assets/js/custom/modals/create-app.js') }}"></script> -->
<!-- <script src="{{ asset('assets/js/custom/modals/upgrade-plan.js') }}"></script> -->
<!--end::Page Custom Javascript-->

<!-- Additional Scripts -->
<!-- <script src="{{ asset('assets/js/custom/account/settings/signin-methods.js') }}"></script> -->

<!-- Optional: If these are needed later -->
{{-- 
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('assets/js/custom/documentation/editors/quill/autosave.js') }}"></script>
--}}
<!--begin::CRM Data Security - Disable Copy & Cut Script-->
<script>
(function() {
    // Helper function to determine if the target element is an editable input or textarea
    function isEditableElement(target) {
        if (!target) return false;
        var tagName = target.tagName ? target.tagName.toUpperCase() : '';
        if (tagName === 'INPUT' || tagName === 'TEXTAREA') {
            return true;
        }
        if (target.isContentEditable || target.closest('[contenteditable="true"]')) {
            return true;
        }
        if (target.classList && target.classList.contains('allow-select')) {
            return true;
        }
        return false;
    }

    // 1. Block Copy Event across the document (unless inside input/textarea)
    document.addEventListener('copy', function(e) {
        if (!isEditableElement(e.target)) {
            e.preventDefault();
            if (e.clipboardData) {
                e.clipboardData.clearData();
            }
            return false;
        }
    }, true);

    // 2. Block Cut Event
    document.addEventListener('cut', function(e) {
        if (!isEditableElement(e.target)) {
            e.preventDefault();
            return false;
        }
    }, true);

    // 3. Block Keyboard Shortcuts (Ctrl+C, Ctrl+X, Ctrl+A outside editable elements)
    document.addEventListener('keydown', function(e) {
        var isCtrlOrCmd = e.ctrlKey || e.metaKey;
        if (isCtrlOrCmd && !isEditableElement(e.target)) {
            var key = e.key ? e.key.toLowerCase() : '';
            if (key === 'c' || key === 'x' || key === 'a' || key === 'u') {
                e.preventDefault();
                return false;
            }
        }
    }, true);

    // 4. Global Utility Helper for future Copy Buttons
    window.crmCopyToClipboard = function(text, successMessage) {
        if (!text) return;
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                if (typeof toastr !== 'undefined') {
                    toastr.success(successMessage || 'Copied to clipboard!');
                } else {
                    alert(successMessage || 'Copied to clipboard!');
                }
            }).catch(function(err) {
                fallbackCopy(text, successMessage);
            });
        } else {
            fallbackCopy(text, successMessage);
        }
    };

    function fallbackCopy(text, successMessage) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            if (typeof toastr !== 'undefined') {
                toastr.success(successMessage || 'Copied to clipboard!');
            }
        } catch (err) {
            console.error('Fallback copy failed', err);
        }
        document.body.removeChild(textArea);
    }
})();
</script>
<!--end::CRM Data Security-->
<!--end::Javascript-->
