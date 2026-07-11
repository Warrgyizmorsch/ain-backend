<div class="modal fade" id="userGroupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">User Groups</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="userGroupForm">
                <div class="modal-body">
                    <div id="userGroupError" class="alert alert-danger d-none"></div>
                    <label class="form-label fw-bold">Select Groups</label>
                    <div class="d-flex gap-2 align-items-start">
                        <select id="userGroupSelect" name="group_ids[]" class="form-select" multiple style="width:100%"></select>
                        <button type="button" id="openNewGroupModal" class="btn btn-light-success text-nowrap"><i class="fa fa-plus me-1"></i>Add Group</button>
                    </div>
                    <div class="form-text">You can select more than one group.</div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Groups</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="newUserGroupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Add New Group</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="newUserGroupForm">
                <div class="modal-body"><div id="newUserGroupError" class="alert alert-danger d-none"></div><label class="form-label fw-bold">Group Name</label><input id="newUserGroupName" class="form-control" maxlength="255" required placeholder="Enter group name"></div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success">Add Group</button></div>
            </form>
        </div>
    </div>
</div>

<script>
window.userGroupOptions = @json(\App\Models\GroupMaster::where('status', 1)->orderBy('name')->get(['id','name']));

function renderUserGroupSelect(selectedIds) {
    const select = $('#userGroupSelect');
    select.empty();
    window.userGroupOptions.forEach(group => select.append(new Option(group.name, group.id, false, selectedIds.map(String).includes(String(group.id)))));
    if (select.hasClass('select2-hidden-accessible')) select.select2('destroy');
    select.select2({dropdownParent: $('#userGroupModal'), placeholder:'Select groups', width:'100%', closeOnSelect:false});
}

window.openUserGroupModal = function(userId, userName, assigned) {
    const modal = document.getElementById('userGroupModal');
    modal.dataset.userId = userId;
    modal.querySelector('.modal-title').textContent = 'Groups - ' + userName;
    document.getElementById('userGroupError').classList.add('d-none');
    renderUserGroupSelect(assigned || []);
    bootstrap.Modal.getOrCreateInstance(modal).show();
};

document.getElementById('openNewGroupModal').addEventListener('click', function() {
    document.getElementById('newUserGroupName').value = '';
    document.getElementById('newUserGroupError').classList.add('d-none');
    bootstrap.Modal.getOrCreateInstance(document.getElementById('newUserGroupModal')).show();
});

document.getElementById('newUserGroupForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const name = document.getElementById('newUserGroupName').value.trim();
    const error = document.getElementById('newUserGroupError');
    try {
        const body = new FormData();
        body.append('new_group_name', name);
        $('#userGroupSelect').val().forEach(id => body.append('group_ids[]', id));
        const userId = document.getElementById('userGroupModal').dataset.userId;
        const res = await fetch(`{{ url('/group-master/user') }}/${userId}/assign`, {method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body});
        const data = await res.json(); if (!res.ok) throw new Error(data.message || 'Unable to create group.');
        data.groups.forEach(group => { if (!window.userGroupOptions.some(item => String(item.id) === String(group.id))) window.userGroupOptions.push(group); });
        window.userGroupOptions.sort((a,b) => a.name.localeCompare(b.name));
        renderUserGroupSelect(data.groups.map(group => group.id));
        bootstrap.Modal.getInstance(document.getElementById('newUserGroupModal')).hide();
        updateUserGroupBadges(userId, data.groups);
    } catch (ex) { error.textContent = ex.message; error.classList.remove('d-none'); }
});

function updateUserGroupBadges(userId, groups) {
    document.querySelectorAll(`[data-user-group-badges="${userId}"]`).forEach(el => el.innerHTML = groups.map(group => `<span class="badge badge-light-primary fs-8 me-1">${$('<div>').text(group.name).html()}</span>`).join(''));
    document.querySelectorAll(`[data-user-group-button="${userId}"]`).forEach(button => button.dataset.groups = JSON.stringify(groups.map(group => group.id)));
}

document.getElementById('userGroupForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const modal = document.getElementById('userGroupModal'), error = document.getElementById('userGroupError'), body = new FormData();
    ($('#userGroupSelect').val() || []).forEach(id => body.append('group_ids[]', id));
    try {
        const res = await fetch(`{{ url('/group-master/user') }}/${modal.dataset.userId}/assign`, {method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body});
        const data = await res.json(); if (!res.ok) throw new Error(data.message || 'Unable to update groups.');
        updateUserGroupBadges(modal.dataset.userId, data.groups);
        bootstrap.Modal.getInstance(modal).hide();
        if (window.Swal) Swal.fire({icon:'success',title:'Groups updated',timer:1000,showConfirmButton:false});
    } catch (ex) { error.textContent = ex.message; error.classList.remove('d-none'); }
});
</script>
