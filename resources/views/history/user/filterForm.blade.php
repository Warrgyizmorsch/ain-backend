<style>
    .filter-div {
        margin: -50px 30px 40px 30px;
    }

    #filterForm {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-bottom: 24px;
        align-items: end;
    }

    .flex-column {
        display: flex;
        flex-direction: column;
    }

    #filterForm label {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 4px;
    }

    #filterForm input {
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        outline: none;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    #filterForm input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }

    #filterForm select {
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        outline: none;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    #filterForm select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }


    .button-group {
        display: flex;
        gap: 8px;
        margin-top: 16px;
    }

    .btn {
        font-size: 12px;
        padding: 8px 14px !important;
    }

    @media screen and (max-width: 768px) {
        #filterForm {
            grid-template-columns: 1fr;
        }

        .filter-div {
            margin: 0 10px 40px 10px;
        }

        .button-group {
            margin-top: 5px;
        }
    }

    @media screen and (max-width: 992px) {
        .filter-div {
            margin: 0 20px 40px 20px;
        }
    }
</style>

<div class="filter-div">
    <div style="font-size: 24px; font-weight: 600; margin-bottom: 20px;">Filter</div>
    <form id="filterForm">
        <div class="flex-column">
            <label for="role_id">User Role</label>
            <select name="role_id" id="role_id">
                <option value="">Select Role</option>
                @foreach ($userRole as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->role }}
                    </option>
                @endforeach
            </select>
        </div>

    
        <div class="flex-column">
            <label for="search">Search</label>
            <input type="text" name="search" id="search" placeholder="Name or Email" value="{{ request('search') }}">
        </div>
    
        <div class="flex-column">
            <label for="from">From</label>
            <input type="date" name="from" id="from" value="{{ request('from') }}">
        </div>
    
        <div class="flex-column">
            <label for="to">To</label>
            <input type="date" name="to" id="to" value="{{ request('to') }}">
        </div>
    
        <div class="button-group">
            <button type="submit" class="btn btn-primary">Search</button>
            <button type="button" id="resetFilter" class="btn btn-danger">Reset</button>
        </div>
    </form>
</div>