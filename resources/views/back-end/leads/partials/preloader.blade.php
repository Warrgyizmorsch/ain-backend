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

        .action-checkbox {
            width: 18px !important;
            height: 18px !important;
            border: 2px solid #64748b !important;
            border-radius: 4px !important;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .action-checkbox:checked {
            background-color: #009ef7 !important;
            border-color: #009ef7 !important;
        }
        .action-reason-dropdown {
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 6px !important;
            background-color: #f8fafc !important;
            color: #1e293b !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .action-reason-dropdown:hover, .action-reason-dropdown:focus {
            border-color: #009ef7 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(0, 158, 247, 0.15) !important;
        }
        .assign-toggle {
            transform: scale(1.05);
            cursor: pointer;
        }
        .assign-toggle:not(:checked) {
            background-color: #FFC107 !important;
            border-color: #e0a800 !important;
        }
        .assign-toggle:checked {
            background-color: #28a745 !important;
            border-color: #1e7e34 !important;
        }

        .lead-comment-box {
            background: #f5f8fa;
            border: 1px solid #e4e6ef;
            border-radius: 8px;
            padding: 12px 14px;
            width: 260px;
            min-height: 92px;
            margin: 0 auto;
            text-align: left;
            box-shadow: none;
        }

        .lead-comment-box .text-gray-800 {
            color: #3f4254;
            font-size: 14px;
            line-height: 1.4;
        }

        .lead-comment-box .fs-8 {
            font-size: 12px !important;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .modal.fade.custom-slide-right .modal-dialog {
            position: fixed;
            top: 0;
            right: 0;
            margin: 0;
            height: 100vh;
            width: 40%;
            transform: translateX(100%);
            transition: transform 0.4s ease-out;
            display: flex;
            flex-direction: column;
        }

        .modal.fade.custom-slide-right.show .modal-dialog {
            transform: translateX(0);
            animation: slideInRight 0.4s ease-out;
            border-radius: 0;
        }

        .modals {
            height: 100vh;
            display: flex;
            flex-direction: column;
            border-radius: 0;
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .modal.fade.custom-slide-right .modal-dialog {
                width: 100%;
            }
        }

        /* Prevent full-screen overlay from blocking the page */
        #preloader {
            display: none !important;
        }
    </style>
    <div id="preloader" style="display:none !important;"></div>