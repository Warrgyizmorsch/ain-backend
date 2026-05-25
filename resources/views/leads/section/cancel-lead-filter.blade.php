<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Filter</span>
        </h3>
    </div>
    <div class="card-body py-3">
        <form action="">
            <div class="row mb-3">
                <div class="col-md-3 fv-row">
                    <input type="search" name="search" id="search" class="form-control form-control-solid" placeholder="Serach By OrderCode">
                </div>

                <div class="col-md-3 fv-row">
                    <input type="search" name="uid" id="search" class="form-control form-control-solid" placeholder="Serach By Number">
                </div>

                <div class="col-md-3 fv-row">
                <input type="date" name="additional_filter3" id="additional_filter3" class="form-control form-control-solid" placeholder="Upto ">
            </div>

            <div class="col-md-3 fv-row">
                <input type="date" name="additional_filter6" id="additional_filter6" class="form-control form-control-solid" placeholder="Upto ">
            </div>

           
                
            </div>
            <div class="row mb-3">

                <div class="col-md-3 fv-row">
                    <select name="additional_filter7" id="additional_filter7" class="form-select form-select-solid" placeholder="Additional Filter 4">
                        <option value="">Date Type</option>
                        <option value="Deadline">Deadline</option>
                    </select>
                </div>
            </div>
            

            <div class="row">
                <div class="col-lg-12 fv-row fv-plugins-icon-container">
                    <button type='submit' class="btn btn-sm btn-primary">Search</button>
                    <a href="/c-leads" class="btn btn-sm btn-danger">reset</a>
                </div>
            </div>
        </form>
    </div>
</div>
