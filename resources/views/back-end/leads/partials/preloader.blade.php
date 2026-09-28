<!-- Styles -->
    <style>
        .loading-spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solid #009ef7;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        table.table td, table.table th {
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }

        /* Prevent full-screen overlay from blocking the page */
        #preloader {
            display: none !important;
        }
    </style>
    <div id="preloader" style="display:none !important;"></div>