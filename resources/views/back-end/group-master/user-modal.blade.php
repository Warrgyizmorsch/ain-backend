<div class="modal fade" id="userGroupModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">User Groups</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><form id="userGroupForm"><div class="modal-body"><div id="userGroupError" class="alert alert-danger d-none"></div><label class="form-label fw-bold">Existing Groups</label><div id="userGroupChoices" class="d-flex flex-column gap-2 mb-4"></div><div class="input-group"><input id="newUserGroupName" class="form-control" placeholder="New group name"><button type="button" id="addNewUserGroup" class="btn btn-light-primary" title="Create group">+</button></div></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save Groups</button></div></form></div></div></div>
<script>
window.userGroupOptions = @json(\App\Models\GroupMaster::where('status', 1)->orderBy('name')->get(['id','name']));
window.openUserGroupModal = function(userId, userName, assigned) {
    const modalEl = document.getElementById('userGroupModal'), choices = document.getElementById('userGroupChoices');
    modalEl.dataset.userId = userId; modalEl.querySelector('.modal-title').textContent = 'Groups - ' + userName;
    const selected = (assigned || []).map(String);
    choices.innerHTML = window.userGroupOptions.map(g => `<label class="form-check form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" name="group_ids[]" value="${g.id}" ${selected.includes(String(g.id))?'checked':''}><span class="form-check-label">${$('<div>').text(g.name).html()}</span></label>`).join('') || '<span class="text-muted">No group created yet.</span>';
    document.getElementById('newUserGroupName').value = ''; document.getElementById('userGroupError').classList.add('d-none');
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
};
document.getElementById('addNewUserGroup').addEventListener('click', () => document.getElementById('userGroupForm').requestSubmit());
document.getElementById('userGroupForm').addEventListener('submit', async function(e) {
    e.preventDefault(); const modal = document.getElementById('userGroupModal'), error = document.getElementById('userGroupError');
    const body = new FormData(this); body.append('new_group_name', document.getElementById('newUserGroupName').value.trim());
    try {
        const res = await fetch(`{{ url('/group-master/user') }}/${modal.dataset.userId}/assign`, {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}, body});
        const data = await res.json(); if (!res.ok) throw new Error(data.message || 'Unable to update groups.');
        window.userGroupOptions = data.groups.reduce((all,g) => all.some(x=>String(x.id)===String(g.id)) ? all : [...all,g], window.userGroupOptions);
        document.querySelectorAll(`[data-user-group-badges="${modal.dataset.userId}"]`).forEach(el => el.innerHTML = data.groups.map(g => `<span class="badge badge-light-primary fs-8 me-1">${$('<div>').text(g.name).html()}</span>`).join(''));
        document.querySelectorAll(`[data-user-group-button="${modal.dataset.userId}"]`).forEach(btn => btn.dataset.groups = JSON.stringify(data.groups.map(g=>g.id)));
        bootstrap.Modal.getInstance(modal).hide(); if (window.Swal) Swal.fire({icon:'success',title:'Groups updated',timer:1000,showConfirmButton:false});
    } catch (ex) { error.textContent = ex.message; error.classList.remove('d-none'); }
});
</script>
