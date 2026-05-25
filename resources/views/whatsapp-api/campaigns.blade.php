@extends('layouts.app')

@section('content')
<style>
    .custom-tab-container {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
        position: relative;
    }

    .custom-tab {
        padding: 10px 25px;
        margin: 0 10px;
        font-weight: 600;
        font-size: 16px;
        border: none;
        background-color: transparent;
        cursor: pointer;
        position: relative;
        color: #888;
        transition: all 0.3s ease;
    }

    .custom-tab.active {
        color: #009ef7;
    }

    .custom-tab::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0%;
        height: 3px;
        background-color: #009ef7;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .custom-tab.active::after {
        width: 100%;
    }
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            <!-- Tab Buttons -->
            <div class="custom-tab-container">
                <button class="custom-tab active" onclick="setTab('all')">All</button>
                <button class="custom-tab" onclick="setTab('broadcast')">Broadcast</button>
            </div>

            <div class="card mb-5 mb-xxl-10">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-flush align-middle table-row-bordered table-row-solid gy-4 gs-9">
                            <thead class="border-gray-200 fs-5 fw-bold bg-lighten">
                                <tr>
                                    <th class="min-w-100px">Location</th>
                                    <th class="min-w-150px">Campaign</th>
                                    <th class="min-w-150px">Type</th>
                                    <th class="min-w-250px">Created At</th>
                                    <th class="min-w-150px">Status</th>
                                    <th class="min-w-100px">Audience</th>
                                    <th class="min-w-100px">Submitted</th>
                                </tr>
                            </thead>
                            <tbody class="fw-6 fw-bold text-gray-600">
                                <tr>
                                    <td>1</td>
                                    <td>WTS Visa MAY</td>
                                    <td>BROADCAST</td>
                                    <td>May 10, 2025, 11:21 AM</td>
                                    <td><span class="badge badge-light-success fs-7 fw-bolder">sent</span></td>
                                    <td>93</td>
                                    <td>93</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Ini</td>
                                    <td>BROADCAST</td>
                                    <td>Apr 26, 2025, 8:07 PM</td>
                                    <td><span class="badge badge-light-success fs-7 fw-bolder">sent</span></td>
                                    <td>99</td>
                                    <td>99</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function setTab(tab) {
        const tabs = document.querySelectorAll('.custom-tab');
        tabs.forEach(t => t.classList.remove('active'));
        if (tab === 'all') {
            tabs[0].classList.add('active');
        } else {
            tabs[1].classList.add('active');
        }

        // Optional: add filtering logic if needed
    }
</script>
@endsection
