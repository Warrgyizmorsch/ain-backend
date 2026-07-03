<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
    	<h3 class="card-title align-items-start flex-column">
    		<span class="card-label fw-bolder fs-3 mb-1">Filter</span>
    	</h3>
    </div>
    <div class="card-body py-3">
    <form action="" id="searchForm">
        <div class="row mb-3">
            <div class="col-md-3 fv-row">
                <input type="search" name="search" id="search" class="form-control form-control-solid" placeholder="Search By OrderCode" value="{{ request('search') }}">
            </div>

             <div class="col-lg-3 fv-row fv-plugins-icon-container">
                <select name="status" id="status" aria-label="Select a Language" data-control="select2" data-placeholder="Status" class="form-select form-select-solid form-select-lg">
                    <option value=""></option>
                    <option value="Issue Raised" {{ request('status') == 'Issue Raised' ? 'selected' : '' }}>Issue Raised</option>
                    <option value="Client Discussion Done" {{ request('status') == 'Client Discussion Done' ? 'selected' : '' }}>Client Discussion Done</option>
                    <option value="Writer discussion Done" {{ request('status') == 'Writer discussion Done' ? 'selected' : '' }}>Writer discussion Done</option>
                    <option value="Work in progress" {{ request('status') == 'Work in progress' ? 'selected' : '' }}>Work in progress</option>
                    <option value="Case Resolved" {{ request('status') == 'Case Resolved' ? 'selected' : '' }}>Case Resolved</option>
                    <option value="Issue Raised Again" {{ request('status') == 'Issue Raised Again' ? 'selected' : '' }}>Issue Raised Again</option>
                    <option value="Retention" {{ request('status') == 'Retention' ? 'selected' : '' }}>Retention</option>
                </select>
                <div class="fv-plugins-message-container invalid-feedback"></div>
            </div>
            @if(auth()->user()->role_id == 1)
            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                <select name="writer" id="writer_select" aria-label="Select a Language" data-control="select2" data-placeholder="Writer" class="form-select form-select-solid form-select-lg">
                    <option value=""></option>
                    @foreach($data['writer'] as $writer)
                    <option value="{{$writer->writer_name}}" {{ request('writer') == $writer->writer_name ? 'selected' : '' }}>{{$writer->writer_name}}</option>
                    @endforeach
                </select>
                <div class="fv-plugins-message-container invalid-feedback"></div>
            </div>
            @endif

            <div class="col-md-3 fv-row">
                <input type="search" name="ticket_no" id="ticket_no" class="form-control form-control-solid" placeholder="Search By Tikcket Number" value="{{ request('ticket_no') }}">
                <input type="hidden" name="team_id" id="filter_team_id" value="{{ request('team_id') }}">
            </div>
            <div class="col-lg-3 fv-row fv-plugins-icon-container">
                    <select name="edited_on" id="edited_on" aria-label="Select Service Type" data-control="select2" class="form-select form-select-solid form-select-lg select2-hidden-accessible" data-select2-id="select2-data-16-796922" tabindex="-1" aria-hidden="true">
                        <option value="">Select O/F date</option>
                        <option value="Order-date" {{ request('edited_on') == 'Order-date' ? 'selected' : '' }}>Order date</option>
                        <option value="Feedback-date" {{ request('edited_on') == 'Feedback-date' ? 'selected' : '' }}>Feedback date</option>
                    </select>
                    <div class="fv-plugins-message-container invalid-feedback"></div>
            </div>
            
            <div class="col-md-3 fv-row">
                <input type="date" name="fromDate" id="fromDate" class="form-control form-control-solid" placeholder="Search By OrderCode" value="{{ request('fromDate') }}">
            </div>
            <div class="col-md-3 fv-row">
                <input type="date" name="toDate" id="toDate" class="form-control form-control-solid" placeholder="Search By OrderCode" value="{{ request('toDate') }}">
            </div>
        </div>

        <div class="col-lg-12 fv-row fv-plugins-icon-container">
            <button type="submit" id="searchButton" class="btn btn-sm btn-primary">Search</button>
            <a href="/feedback" class="btn btn-sm btn-danger">Reset</a>
            <button type="button" id="teamAlphaBtn" class="btn btn-sm btn-info ms-2">
                Team-Alpha {{ $alphaCount ?? 0 }}
            </button>
            <button type="button" id="teamGigaBtn" class="btn btn-sm btn-dark ms-2">
                Team-Giga {{ $gigaCount ?? 0 }}
            </button>
        </div>
    </form>
</div>