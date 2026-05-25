<!-- Styles -->
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(138, 145, 155, 0.8);
            z-index: 1050;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .spinner-border.text-light {
            color: white;
        }
        table.table td, table.table th {
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }
    </style>
        <div id="preloader" style="display:none;">
        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>