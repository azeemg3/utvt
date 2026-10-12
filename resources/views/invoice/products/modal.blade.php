<div class="modal fade" id="add-new">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <form id="form" data-action="{{ route('product.store') }}">
                <input type="hidden" name="id" value="0">
                <x-modal-title title="Product" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name <x-text-danger /></label>
                                <input type="text" class="form-control form-control-sm" name="name" placeholder="Product name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>SKU</label>
                                <input type="text" class="form-control form-control-sm" name="sku" placeholder="SKU">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Price <x-text-danger /></label>
                                <input type="number" step="0.01" min="0" class="form-control form-control-sm" name="price" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control form-control-sm" name="description" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control form-control-sm" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
