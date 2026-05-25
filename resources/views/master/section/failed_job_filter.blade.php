<div class="card card-xxl-stretch mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
    	<h3 class="card-title align-items-start flex-column">
    		<span class="card-label fw-bolder fs-3 mb-1">Filter</span>
    	</h3>
    </div>
    <div class="card-body py-3">
    	<form action="">
    		<div class="row mb-3">
                
                           
    			<div class="col-lg-3 fv-row fv-plugins-icon-container">
    				<select name="order_id"  id="filter"  id="" aria-label="Select a Timezone" data-control="select2" data-placeholder="Search By Order-Code" class="form-select form-select-solid form-select-lg " data-select2-id="select2-data-16-7969" tabindex="-1" >
						<option value="" data-select2-id="select2-data-18-e9lh"></option>
    					@foreach($order_filter as $order)
						<option value="{{ $order->order_id }}">  {{ $order->order_id ?? '' }}</option>
						@endforeach
    				</select>
    			<div class="fv-plugins-message-container invalid-feedback"></div></div>
                <div class="col-lg-3 fv-row fv-plugins-icon-container">
    				<select name="payment_order-id" aria-label="Select a Language" data-control="select2" data-placeholder="Serach By Date" class="form-select form-select-solid form-select-lg " data-select2-id="select2-data-13-mh4q" tabindex="-1" >
    					<option value="" ></option>
                           @foreach($order as $order)
                           <option value=""></option>
                           @endforeach
    				</select>
    				<div class="fv-plugins-message-container invalid-feedback"></div>
    			</div>
                
                
    			</div>
    			<div class="col-lg-3 fv-row fv-plugins-icon-container">
    				<button type='submit' class="btn btn-sm btn-primary" >Search</button>
    				<a href="/failedJobs" class="btn btn-sm btn-danger" >reset</a>
    			</div>
    		</div>
    	</form>

		
    </div>




	