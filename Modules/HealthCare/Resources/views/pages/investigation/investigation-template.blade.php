<div class="row" style="display: none">
    <div class="col-sm-12">
        <h5><b>Add / Update Test</b></h5>
    </div>
    <div class="col-sm-3">
        <label for="">Test Name:</label>
        <input type="text" class="form-control test-name" placeholder="Test Name">
    </div>
    <div class="col-sm-2">
        <label for="">Unit:</label>
        <input type="text" class="form-control unit" placeholder="Unit">
    </div>
    <div class="col-sm-3">
        <label for="">Range Type</label>
        <select name="" id="" class="form-control range-type">
            <option value="1">Normal Range</option>
            <option value="2">Gender Wise Range</option>
            {{-- <option value="3">Age Wise Range</option> --}}
        </select>
    </div>
    <div class="col-sm-2 from-range-holder">
        <label for="">From</label>
        <input type="number" class="form-control from-range">
    </div>
    <div class="col-sm-2 to-range-holder">
        <label for="">To</label>
        <input type="number" class="form-control to-range">
    </div>
    {{-- Gender Wise Ranges --}}
    <div class="col-sm-12 gender-ranges" style="margin-top: 15px; display: none">
        <div class="row">
            <div class="col-sm-2">
                <label for="">From (Male)</label>
                <input type="number" class="form-control from-range-male">
            </div>
            <div class="col-sm-2">
                <label for="">To (Male)</label>
                <input type="number" class="form-control to-range-male">
            </div>
            <div class="col-sm-2">
                <label for="">From (Female)</label>
                <input type="number" class="form-control from-range-female">
            </div>
            <div class="col-sm-2">
                <label for="">To (Female)</label>
                <input type="number" class="form-control to-range-female">
            </div>
        </div>
    </div>
    {{-- Style Fields --}}
    <div class="col-sm-12 style-fields" style="margin-top: 15px">
        <div class="row">
            <div class="col-sm-2">
                <label for="">Font Size (px)</label>
                <input type="number" min="1" max="30" class="form-control font-size">
            </div>
            <div class="col-sm-2">
                <label for="">Font Weight</label>
                <select class="form-control font-weight" id="">
                    <option value="normal">Normal</option>
                    <option value="bold">Bold</option>
                </select>
            </div>
            <div class="col-sm-2">
                <label for="">Font Color</label>
                <input type="color" class="form-control font-color">
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <button class="btn btn-success add-report-btn" style="float: right; margin-top: 15px">Add</button>
        <button class="btn btn-success update-report-btn" data-index-no="" style="float: right; margin-top: 15px">Update</button>
    </div>
</div>
<table class="table table-bordered" style="margin-top: 15px">
    <thead>
        <tr>
            <td colspan="5"><input type="text" class="form-control report-table-title" placeholder="Title"></td>
        </tr>
        <tr>
            <th>Test</th>
            <th>Unit</th>
            <th>Range</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody class="test-list-holder">
        
    </tbody>
    <tbody>
        <tr>
            <td colspan="5" style="text-align: center"><button class="btn btn-primary add-report-view-btn"><i class="fa fa-plus-square"></i></button></td>
        </tr>
    </tbody>
</table>