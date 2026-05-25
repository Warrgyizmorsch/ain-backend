@extends('layouts.app')

@section('content')

<div class="container-fluid user-report-page">

    <!-- HEADER -->
    <div class="report-header mb-4">

        <div>
            <h2>User Report Dashboard</h2>
            <p>Lead / Converted / Retain / Loyal Users Analytics</p>
        </div>

        <form method="GET" class="report-filter">

            <input type="date"
                   name="from"
                   value="{{ $from }}"
                   class="form-control">

            <input type="date"
                   name="to"
                   value="{{ $to }}"
                   class="form-control">

            <button class="btn btn-primary">
                Filter
            </button>

        </form>

    </div>

    <!-- SUMMARY -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="summary-card blue">
                <span>Total Leads</span>
                <h3>{{ $totalLeads }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card green">
                <span>Converted Leads</span>
                <h3>{{ $convertedLeads }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card pink">
                <span>Not Converted Leads</span>
                <h3>{{ $notConvertedLeads }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card dark">
                <span>Total Users</span>
                <h3>{{ $totalUsers }}</h3>
            </div>
        </div>

    </div>

    <!-- TABLES -->
    <div class="row g-4">

        <!-- NOT CONVERTED -->
        <div class="col-lg-6">

            <div class="report-card">

                <div class="report-card-header">
                    <h3>Not Converted Users</h3>
                </div>

                <div class="metric-grid">
                    <div class="metric-box clickable-user"
                        data-type="not_converted_total"
                        data-title="Not Converted - Total Users">

                        <span>Total Users</span>
                        <strong>{{ $totalNotConvertedUsers }}</strong>

                    </div>

                    <div class="metric-box clickable-user"
                         data-type="not_converted_new"
                         data-title="Not Converted - New Users">

                        <span>New Users</span>
                        <strong>{{ $notConvertedNewUsers }}</strong>

                    </div>

                    {{-- <div class="metric-box clickable-user"
                         data-type="not_converted_regular"
                         data-title="Not Converted - Regular Users">

                        <span>Regular Users</span>
                        <strong>{{ $notConvertedRegularUsers }}</strong>

                    </div> --}}

                    <div class="metric-box clickable-user"
                         data-type="not_converted_retain"
                         data-title="Not Converted - Retain Users">

                        <span>Retain Users</span>
                        <strong>{{ $notConvertedRetainUsers }}</strong>

                    </div>

                    <div class="metric-box clickable-user"
                         data-type="not_converted_loyal"
                         data-title="Not Converted - Loyal Users">

                        <span>Loyal Users</span>
                        <strong>{{ $notConvertedLoyalUsers }}</strong>

                    </div>

                </div>

            </div>

        </div>

        <!-- CONVERTED -->
        <div class="col-lg-6">

            <div class="report-card">

                <div class="report-card-header">
                    <h3>Converted Users</h3>
                </div>

                <div class="metric-grid">
                    <div class="metric-box clickable-user"
                        data-type="converted_total"
                        data-title="Converted - Total Users">

                        <span>Total Users</span>
                        <strong>{{ $totalConvertedUsers }}</strong>

                    </div>

                    <div class="metric-box clickable-user"
                         data-type="converted_new"
                         data-title="Converted - New Users">

                        <span>New Users</span>
                        <strong>{{ $convertedNewUsers }}</strong>

                    </div>

                    {{-- <div class="metric-box clickable-user"
                         data-type="converted_regular"
                         data-title="Converted - Regular Users">

                        <span>Regular Users</span>
                        <strong>{{ $convertedRegularUsers }}</strong>

                    </div> --}}

                    <div class="metric-box clickable-user"
                         data-type="converted_retain"
                         data-title="Converted - Retain Users">

                        <span>Retain Users</span>
                        <strong>{{ $convertedRetainUsers }}</strong>

                    </div>

                    <div class="metric-box clickable-user"
                         data-type="converted_loyal"
                         data-title="Converted - Loyal Users">

                        <span>Loyal Users</span>
                        <strong>{{ $convertedLoyalUsers }}</strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- USER TABLE -->
    <div class="user-panel d-none" id="userTableCard">

        <div class="user-panel-card">

            <div class="user-panel-header">

                <div>
                    <h3 id="userTableTitle">
                        User List
                    </h3>

                    <p id="userTableSubTitle">
                        Loading users...
                    </p>
                </div>

                <button id="closeUserTable">
                    ×
                </button>

            </div>

            <!-- PRELOADER -->
            <div class="user-loader d-none" id="tableLoader">

                <div class="loader-spinner"></div>

                <span>Loading Users...</span>

            </div>

            <div class="table-responsive">

                <table class="table user-table mb-0">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile No</th>
                            <th>Created At</th>
                        </tr>

                    </thead>

                    <tbody id="userTableBody">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<style>

.user-report-page{
    padding:20px;
}

.report-header{
    background:#fff;
    padding:25px;
    border-radius:16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 5px 20px rgba(0,0,0,.05);
}

.report-header h2{
    font-size:28px;
    font-weight:700;
    margin-bottom:5px;
}

.report-header p{
    color:#64748b;
    margin:0;
}

.report-filter{
    display:flex;
    gap:10px;
}

.report-filter input{
    min-width:180px;
}

.summary-card{
    border-radius:16px;
    padding:25px;
    color:#fff;
}

.summary-card span{
    display:block;
    margin-bottom:12px;
    font-weight:600;
}

.summary-card h3{
    font-size:38px;
    margin:0;
    color:#fff;
}

.blue{
    background:#0ea5e9;
}

.green{
    background:#22c55e;
}

.pink{
    background:#f43f5e;
}

.dark{
    background:#111827;
}

.report-card{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,.05);
}

.report-card-header{
    padding:20px 25px;
    border-bottom:1px solid #eee;
}

.report-card-header h3{
    margin:0;
    font-size:22px;
    font-weight:700;
}

.metric-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
}

.metric-box{
    padding:25px 10px;
    text-align:center;
    border-right:1px solid #eee;
    cursor:pointer;
    transition:.25s;
}

.metric-box:last-child{
    border-right:none;
}

.metric-box:hover{
    background:#f8fafc;
}

.metric-box.active{
    background:#eff6ff;
}

.metric-box span{
    display:block;
    color:#64748b;
    margin-bottom:10px;
    font-size:14px;
}

.metric-box strong{
    font-size:28px;
    color:#111827;
}

.user-panel{
    margin-top:30px;
}

.user-panel-card{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,.05);
}

.user-panel-header{
    padding:20px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #eee;
}

.user-panel-header h3{
    margin:0;
    font-size:22px;
}

.user-panel-header p{
    margin:4px 0 0;
    color:#64748b;
}
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    PADDING: 20PX;
}

#closeUserTable{
    width:36px;
    height:36px;
    border:none;
    border-radius:50%;
    background:#ef4444;
    color:#fff;
    font-size:24px;
}

.user-loader{
    padding:50px 20px;
    text-align:center;
}

.loader-spinner{
    width:45px;
    height:45px;
    border:4px solid #ddd;
    border-top:4px solid #0d6efd;
    border-radius:50%;
    margin:auto;
    animation:spin .7s linear infinite;
}

.user-loader span{
    display:block;
    margin-top:15px;
    color:#64748b;
}

@keyframes spin{
    100%{
        transform:rotate(360deg);
    }
}

.user-table th{
    background:#f8fafc;
    padding:15px;
}

.user-table td{
    padding:15px;
}

@media(max-width:991px){

    .metric-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .report-header{
        flex-direction:column;
        align-items:flex-start;
    }

}

</style>
<script>

document.querySelectorAll('.clickable-user').forEach(item => {

    item.addEventListener('click', async function () {

        // ACTIVE CLASS
        document.querySelectorAll('.metric-box')
            .forEach(el => el.classList.remove('active'));

        this.classList.add('active');

        // DATA
        let type = this.dataset.type;
        let title = this.dataset.title;

        // HEADER
        document.getElementById('userTableTitle')
            .innerText = title;

        document.getElementById('userTableSubTitle')
            .innerText = 'Fetching users...';

        // SHOW PANEL
        document.getElementById('userTableCard')
            .classList.remove('d-none');

        // SHOW LOADER
        document.getElementById('tableLoader')
            .classList.remove('d-none');

        // CLEAR OLD DATA
        document.getElementById('userTableBody')
            .innerHTML = '';

        let from = '{{ $from }}';
        let to = '{{ $to }}';

        try {

            // API CALL
            let response = await fetch(
                `{{ route('user.report.users') }}?type=${type}&from=${from}&to=${to}`
            );

            // JSON RESPONSE
            let responseData = await response.json();

            // MAIN USERS ARRAY
            let users = responseData.data || [];

            // HIDE LOADER
            document.getElementById('tableLoader')
                .classList.add('d-none');

            // COUNT
            document.getElementById('userTableSubTitle')
                .innerText = users.length + ' users found';

            let html = '';

            // EMPTY DATA
            if (users.length === 0) {

                html = `
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            No Users Found
                        </td>
                    </tr>
                `;

            } else {

                // LOOP USERS
                users.forEach((user, index) => {
                    html += `
                        <tr>
                            <td>${index+1}</td>
                            <td>${user.name ?? '-'}</td>
                           
                            <td>${user.email ?? '-'}</td>
                            <td>${user.mobile_no ?? '-'}</td>
                            <td>${formatDate(user.created_at)}</td>
                        </tr>
                    `;

                });

            }

            // APPEND TABLE
            document.getElementById('userTableBody')
                .innerHTML = html;

            // SCROLL
            setTimeout(() => {

                document.getElementById('userTableCard')
                    .scrollIntoView({
                        behavior: 'smooth'
                    });

            }, 100);

        } catch (error) {

            console.error(error);

            document.getElementById('tableLoader')
                .classList.add('d-none');

            document.getElementById('userTableSubTitle')
                .innerText = 'Failed to load users';

            document.getElementById('userTableBody')
                .innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-danger py-5">
                            Error loading users
                        </td>
                    </tr>
                `;
        }

    });

});

// CLOSE PANEL
document.getElementById('closeUserTable')
    .addEventListener('click', function () {

        document.getElementById('userTableCard')
            .classList.add('d-none');

        document.querySelectorAll('.metric-box')
            .forEach(el => el.classList.remove('active'));

    });

// DATE FORMAT
function formatDate(date) {

    if (!date) {
        return '-';
    }

    let d = new Date(date);

    return d.toLocaleString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

}

</script>
@endsection