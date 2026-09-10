<script>
    let offset = {{count($leads)}};
    const leadPageSize = 30;
    let activeLeadFilters = null;
    const leadLoadMorePath = @json(route('lead.loadMore'));
    const leadFilterPath = @json(route('lead.filter'));
    const leadCsrfToken = $('meta[name="csrf-token"]').attr('content');

    function leadUrl(path) {
        return path;
    }

    function updateLoadMoreVisibility(hasMore) {
        if (hasMore) {
            $('#load-more-wrapper').show();
            $('#load-more').show();
        } else {
            $('#load-more-wrapper').hide();
        }
    }

    function refreshLeadSrNumbers() {
        $('#lead-rows > tr[id^="lead-"]').each(function(index) {
            $(this).children('td:first').text(index + 1);
        });
    }

    function setActiveLeadTab(status) {
        $('.lead-status-tabs .nav-link').removeClass('active');
        $(`.lead-status-tabs .nav-link[data-status="${status}"]`).addClass('active');
    }

    function clearLeadFilters() {
        $('#search_order').val('');
        $('#status_filter').val('').trigger('change');
        $('#lead_status_tab').val('');
        $('#type_filter').val('').trigger('change');
        $('#date_from').val('');
        $('#date_to').val('');
        $('#date_type').val('').trigger('change');
        $('#assign_type').val('').trigger('change');
        $('#selectedValue').val('');
        $('#searchInput').val('');
        $('#lead_source').val('').trigger('change');
        $('#lead_group_id').val('').trigger('change');
        $('#searchResultss').hide().empty();
    }

    $('#load-more').on('click', function() {
        $('#preloader').show();
        const loadMoreFilters = activeLeadFilters ? {
            ...activeLeadFilters,
            offset: offset,
            limit: leadPageSize
        } : {
            offset: offset
        };

        $.ajax({
            url: activeLeadFilters ? leadFilterPath : leadLoadMorePath,
            type: activeLeadFilters ? 'POST' : 'GET',
            headers: activeLeadFilters ? {
                'X-CSRF-TOKEN': leadCsrfToken
            } : {},
            data: loadMoreFilters,
            success: function(res) {
                if (res.html) {
                    $('#lead-rows').append(res.html);
                    refreshLeadSrNumbers();
                }
                if (window.initLeadStars) {
                    window.initLeadStars(document.getElementById('lead-rows'));
                }
                offset = res.next_offset ?? (offset + (res.count || 0));
                updateLoadMoreVisibility(res.has_more);
            },
            error: function() {
                alert('Failed to load more leads.');
            },
            complete: function() {
                $('#preloader').hide();
                document.documentElement.classList.remove('lead-filter-restoring');
            }
        });
    });

    function applyFilters(filters) {
        $('#preloader').show();
        activeLeadFilters = filters;
        $.ajax({
            url: leadFilterPath,
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': leadCsrfToken
            },
            data: filters,
            success: function(res) {
                if (res.html && res.html.trim() !== '') {
                    $('#lead-rows').html(res.html);
                    refreshLeadSrNumbers();
                } else {
                    $('#lead-rows').html('<tr><td colspan="11" class="text-center text-muted py-5"><i class="fa fa-folder-open-o fs-3 text-gray-400 d-block mb-2"></i>No leads found matching your criteria.</td></tr>');
                }
                if (window.initLeadStars) {
                    window.initLeadStars(document.getElementById('lead-rows'));
                }
                offset = res.next_offset ?? (res.count || 0);
                updateLoadMoreVisibility(res.has_more);
            },
            error: function() {
                Swal.fire('Error', 'Failed to load leads.', 'error');
            },
            complete: function() {
                $('#preloader').hide();
                document.documentElement.classList.remove('lead-filter-restoring');
                $('#export-btn').show();
            }
        });
    }

    // Toggle Filter Body
    $('#toggleFilterBtn').on('click', function () {
        $('#filterBody').slideToggle(300, function() {
            if ($('#filterBody').is(':visible')) {
                $('#toggleFilterBtn').text('Hide Filters').removeClass('btn-primary').addClass('btn-danger');
            } else {
                $('#toggleFilterBtn').text('Show Filters').removeClass('btn-danger').addClass('btn-primary');
            }
        });
    });

    // Reset Filters Button
    $(document).on('click', '#resetFiltersBtn', function(e) {
        e.preventDefault();
        localStorage.removeItem('lead_filters');
        clearLeadFilters();
        $(this).hide();
        applyFilters({});
    });

    $(document).on('click', '#applyButton', function(e) {
        e.preventDefault();

        const searchOrderVal = ($('#search_order').val() || '').trim();
        const searchUserVal = ($('#searchInput').val() || '').trim();
        const selectedUidVal = ($('#selectedValue').val() || '').trim();

        const filters = {
            search: searchOrderVal || searchUserVal || '',
            order: searchOrderVal,
            user: searchUserVal,
            selectedValue: selectedUidVal,
            status: $('#status_filter').val() || '',
            lead_status_tab: $('#lead_status_tab').val() || '',
            type: $('#type_filter').val() || '',
            date_from: $('#date_from').val() || '',
            date_to: $('#date_to').val() || '',
            date_type: $('#date_type').val() || '',
            assign_type: String($('#assign_type').val() ?? ''),
            lead_source: $('#lead_source').val() || '',
            group_id: $('#lead_group_id').val() || ''
        };

        const hasFilters = Object.values(filters).some(val => {
            if (val === null || val === undefined) return false;
            return String(val).trim() !== "";
        });

        if (!hasFilters) {
            Swal.fire({
                icon: 'warning',
                title: 'No filters applied!',
                text: 'Please fill at least one filter to search.',
            });
            return;
        }

        $('#resetFiltersBtn').show();
        localStorage.setItem('lead_filters', JSON.stringify(filters));
        applyFilters(filters);
    });

    $(document).on('keypress', '#search_order, #searchInput', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#applyButton').click();
        }
    });

    // Customer Autocomplete Custom Dropdown
    let leadSearchTimeout = null;

    $('#searchInput').on('input focus', function() {
        var searchValue = $(this).val().trim();
        clearTimeout(leadSearchTimeout);

        if (searchValue.length >= 2) {
            $('#searchResultss').html(
                '<div class="p-3 text-center text-muted fs-7 d-flex align-items-center justify-content-center gap-2">' +
                    '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>' +
                    '<span>Searching users...</span>' +
                '</div>'
            ).show();

            leadSearchTimeout = setTimeout(function() {
                $.ajax({
                    url: "{{ route('search-order') }}",
                    type: "GET",
                    data: {
                        user: searchValue
                    },
                    success: function(response) {
                        var resultsHtml = '';
                        if (response && response.length > 0) {
                            $.each(response, function(key, value) {
                                var mobileStr = value.mobile_no ? ' | 📞 ' + value.mobile_no : '';
                                resultsHtml += '<a href="javascript:void(0)" class="dropdown-item user-select-item p-3 border-bottom text-wrap" ' +
                                    'data-id="' + value.id + '" data-email="' + (value.email || '') + '" data-name="' + (value.name || '') + '">' +
                                    '<div class="fw-bolder text-dark fs-6">' + (value.name || 'No Name') + '</div>' +
                                    '<div class="text-muted fs-7">' + (value.email || '') + mobileStr + '</div>' +
                                    '</a>';
                            });
                        } else {
                            resultsHtml = '<div class="p-3 text-muted fs-7 text-center">No results found</div>';
                        }
                        $('#searchResultss').html(resultsHtml).show();
                    },
                    error: function() {
                        $('#searchResultss').html('<div class="p-3 text-danger fs-7 text-center">Error loading results</div>').show();
                    }
                });
            }, 250);
        } else {
            $('#searchResultss').hide().empty();
            if (searchValue.length === 0) {
                $('#selectedValue').val('');
            }
        }
    });

    // Handle click on custom dropdown item
    $(document).on('click', '#searchResultss .user-select-item', function(e) {
        e.preventDefault();
        var selectedId = $(this).attr('data-id');
        var selectedEmail = $(this).attr('data-email');
        var selectedName = $(this).attr('data-name');

        $('#searchInput').val(selectedName + (selectedEmail ? ' (' + selectedEmail + ')' : ''));
        $('#selectedValue').val(selectedId);
        $('#searchResultss').hide().empty();
    });

    // Close dropdown on clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#searchInput, #searchResultss').length) {
            $('#searchResultss').hide();
        }
    });

    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const searchParam = urlParams.get('search') || urlParams.get('order') || urlParams.get('search_order') || urlParams.get('phone') || urlParams.get('user');
        const uidParam = urlParams.get('uid');
        const statusParam = urlParams.get('status') || urlParams.get('lead_status_tab');

        if (searchParam || uidParam || statusParam) {
            localStorage.removeItem('lead_filters');

            if (searchParam) {
                $('#search_order').val(searchParam);
                $('#searchInput').val(searchParam);
            }
            if (uidParam) {
                $('#selectedValue').val(uidParam);
            }
            if (statusParam) {
                $('#status_filter').val(statusParam).trigger('change');
                $('#lead_status_tab').val(statusParam);
                setActiveLeadTab(statusParam);
            }

            const urlFilters = {
                order: searchParam || '',
                user: searchParam || '',
                status: statusParam || '',
                lead_status_tab: statusParam || '',
                type: '',
                date_from: '',
                date_to: '',
                date_type: '',
                assign_type: '',
                selectedValue: uidParam || '',
                lead_source: '',
                group_id: ''
            };

            if (searchParam || uidParam) {
                $('#filterBody').show();
                $('#toggleFilterBtn').text('Hide Filters').removeClass('btn-primary').addClass('btn-danger');
                $('#resetFiltersBtn').show();
            }

            $('#load-more-wrapper').hide();
            applyFilters(urlFilters);
            return;
        }

        let savedLeadFilters = localStorage.getItem('lead_filters');

        if (savedLeadFilters) {
            try {
                const filters = JSON.parse(savedLeadFilters);
                const hasFormFilters = !!(filters.order || filters.user || filters.status || filters.type || filters.date_from || filters.date_to || filters.date_type || filters.assign_type || filters.selectedValue || filters.lead_source || filters.group_id);

                $('#search_order').val(filters.order || '');
                $('#searchInput').val(filters.user || '');
                $('#status_filter').val(filters.status || '').trigger('change');
                $('#lead_status_tab').val(filters.lead_status_tab || '');
                $('#type_filter').val(filters.type || '').trigger('change');
                $('#date_from').val(filters.date_from || '');
                $('#date_to').val(filters.date_to || '');
                $('#date_type').val(filters.date_type || '').trigger('change');
                $('#assign_type').val(filters.assign_type || '').trigger('change');
                $('#selectedValue').val(filters.selectedValue || '');
                $('#lead_source').val(filters.lead_source || '').trigger('change');
                $('#lead_group_id').val(filters.group_id || '').trigger('change');
                setActiveLeadTab(filters.lead_status_tab || 'All');
                $('#load-more-wrapper').hide();

                if (hasFormFilters) {
                    $('#filterBody').show();
                    $('#toggleFilterBtn').text('Hide Filters').removeClass('btn-primary').addClass('btn-danger');
                    $('#resetFiltersBtn').show();
                }

                applyFilters(filters);
            } catch (e) {
                console.error('Error loading saved filters:', e);
            }
        }
    });
</script>
<!-- <script>
    async function convert(button, leadId) {
        const url = `/lead/conver/${leadId}`;
        const row = document.getElementById(`lead-${leadId}`);
        const btn = button;

        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || 'Request failed');
            }

            const data = await response.json();
            console.log('Success:', data);

            btn.innerHTML = '<i class="fa fa-check text-success"></i>';

            setTimeout(() => {
                row.style.transition = 'opacity 0.5s ease';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 500);
            }, 500);
        } catch (error) {
            console.error('Error:', error);
            btn.innerHTML = '<i class="fa fa-exclamation-triangle text-danger"></i>';
            Swal.fire('Error', error.message || 'Lead conversion failed.', 'error');
        } finally {
            btn.disabled = false;
        }
    }
</script> -->

<script>
    async function convert(button, leadId) {

        const confirmResult = await Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to convert this lead?',
            html: `
            <select id="convert_type" class="form-select form-select-solid" style="margin-top:10px;">
                <option value="">Select Type</option>
                <option value="Original">Original</option>
                <option value="Discounted">Discounted</option>
                <option value="Special Price">Special Price</option>
            </select>
        `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, convert it!',
            cancelButtonText: 'No',
            preConfirm: () => {
                const convertType = document.getElementById('convert_type').value;
                if (!convertType) {
                    Swal.showValidationMessage('Please select convert type.');
                    return false;
                }
                return convertType;
            }
        });

        if (!confirmResult.isConfirmed) {
            return; // cancel → kuch nahi hoga
        }

        const convertType = confirmResult.value;


       const url = "{{ route('lead.convert', ':id') }}".replace(':id', leadId);
        const row = document.getElementById(`lead-${leadId}`);
        const btn = button;

        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    convert_type: convertType
                })
            });

            if (!response.ok) throw new Error('Request failed');

            const data = await response.json();
            console.log('Success:', data);

            btn.innerHTML = '<i class="fa fa-check text-success"></i>';

            setTimeout(() => {
                row.style.transition = 'opacity 0.5s ease';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 500);
            }, 500);
        } catch (error) {
            console.error('Error:', error);
            btn.innerHTML = '<i class="fa fa-exclamation-triangle text-danger"></i>';
        } finally {
            btn.disabled = false;
        }
    }
</script>


<script>
    function handleChange(checkbox, leadId) {
        // If unchecked (toggle off)
        if (!checkbox.checked) {
            Swal.fire({
                title: 'Cancel Lead',
                text: 'Are you sure you want to cancel this lead?',
                icon: 'warning',
                input: 'textarea',
                inputPlaceholder: 'Type your message here...',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Please enter a message!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let message = result.value;
                    const row = document.getElementById(`lead-${leadId}`);
                    const buttons = row.querySelectorAll('button, input[type="checkbox"]');

                    // Disable all buttons and checkbox in this row to prevent interaction
                    buttons.forEach(el => el.disabled = true);

                    // Optionally show a spinner on the checkbox
                    const originalCheckboxHTML = checkbox.outerHTML;
                    checkbox.outerHTML = `<i class="fa fa-spinner fa-spin" style="font-size: 18px;"></i>`;

                    // Send AJAX PUT request to cancel lead
                    $.ajax({
                        url: `lead/cancel/${leadId}`, // Make sure your route matches this URL and method
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            message: message
                        },
                        success: function(response) {
                            // Hide the entire row on success
                            row.style.transition = 'opacity 0.5s ease';
                            row.style.opacity = '0';
                            setTimeout(() => {
                                row.remove();
                                // Show a toast or alert message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Lead cancelled successfully',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }, 500);
                        },
                        error: function(xhr, status, error) {
                            console.log("Status:", status);
                            console.log("Error:", error);
                            console.log("Response:", xhr.responseText);
                            // On error, re-enable buttons and checkbox, reset checkbox state
                            buttons.forEach(el => el.disabled = false);

                            // Replace spinner with checkbox again
                            const checkboxContainer = row.querySelector('td .form-check');
                            checkboxContainer.innerHTML = `<input class="form-check-input" type="checkbox" id="${leadId}" role="switch" checked onchange="handleChange(this, ${leadId})">`;

                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to cancel lead',
                                text: error || 'Please try again later.'
                            });
                        }
                    });
                } else {
                    // User canceled SweetAlert, revert checkbox to checked
                    checkbox.checked = true;
                }
            });
        }
    }
</script>


<script>
    function loadTemplates(userId) {
        $('#preloader').show();

        fetch(`{{ route('lead.fetchTemplates', ['userId' => '__USER_ID__']) }}`.replace('__USER_ID__', userId))
            .then(response => response.json())
            .then(data => {
                $('#preloader').hide();

                const dropdown = document.getElementById('templateDropdown');
                dropdown.innerHTML = '<option value="">Select Template</option>';

                if (data.templates && data.templates.length > 0) {
                    data.templates.forEach(template => {
                        const option = document.createElement('option');
                        option.value = template.id;
                        option.text = template.name;
                        dropdown.appendChild(option);
                    });

                    $('#templateModal').modal('show'); // Bootstrap still needs jQuery
                } else {
                    alert('No approved templates found.');
                }
            })
            .catch(error => {
                console.error(error);
                $('#preloader').show();
                alert('Failed to load templates.');
            });
    }

    document.getElementById('templateDropdown').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('templateName').value = selectedOption.text;
    });
</script>
<script>
    function loadchat(leadId) {
        $.ajax({
            url: `{{ route('lead.chat', ':leadId') }}`.replace(':leadId', leadId),
            method: 'GET',
            success: function(response) {
                $('#chatContent').html(response);
                $('#chatModal').modal('show');
                $('#chatModal').attr('data-lead-id', leadId); // store leadId for sending
            },
            error: function() {
                $('#chatContent').html('<p class="text-danger">Failed to load chat.</p>');
                $('#chatModal').modal('show');
            }
        });
    }

    function sendChatMessage() {
        let leadId = $('#chatModal').attr('data-lead-id');
        let message = $('#chatInput').val().trim();

        if (message === '') return;

        $.ajax({
            url: `{{ route('chat.send') }}`,
            method: 'POST',
            data: {
                lead_id: leadId,
                message: message,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#chatInput').val('');
                if (response.chat) {
                    updateLeadRecentChat(response.chat);
                }
                loadchat(leadId); // reload chat after send
            },
            error: function() {
                alert('Message failed to send.');
            }
        });
    }

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    function updateLeadRecentChat(chat) {
        const target = $(`#lead-recent-chat-${chat.lead_id}`);
        if (!target.length) return;

        target.html(`
            <div class="lead-comment-box lead-recent-chat-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-gray-800">${escapeHtml(chat.user_name || 'User')}</span>
                    <span class="text-muted fs-8">${escapeHtml(chat.created_at_human || '')}</span>
                </div>
                <div class="text-gray-800 mb-2">${escapeHtml(chat.description || 'No comment')}</div>
                <div class="text-primary fw-bold fs-7">
                    <i class="fa fa-calendar-alt me-1"></i>
                    ${escapeHtml(chat.created_at_formatted || '')}
                </div>
            </div>
        `);
    }
</script>


<script>
    function checkedLead(checkbox, leadId) {
        $.ajax({
            url: 'checklead/' + leadId,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (checkbox.checked) {
                    console.log("Lead with ID " + leadId + " cancelled successfully.");
                } else {
                    console.log("Lead with ID " + leadId + " restored successfully.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error);
            }
        });
    }
</script>

{{-- Rahul Changes --}}

{{-- <script>
    function filterByStatusTab(statusName, element) {
        // 1. UI: Set Active Class
        $('.nav-link').removeClass('active');
        $(element).addClass('active');

        // 2. Set Status in the existing Status Dropdown (optional, but keeps sync)
        if (statusName === 'All') {
            $('#status_filter').val('').trigger('change');
        } else {
            $('#status_filter').val(statusName).trigger('change');
        }

        // 3. Trigger existing Search Button click to refresh table with AJAX
        $('#applyButton').click();
    }
</script> --}}

<script>
//     function filterByStatusTab(statusName, element) {
//     $('.nav-link').removeClass('active');
//     $(element).addClass('active');

//     if (statusName === 'All') {
//         $('#lead_status_tab').val('All');
//         $('#status_filter').val('').trigger('change');
//     } 
//     else if (statusName === 'Hot' || statusName === 'Warm' || statusName === 'Cold') {
//         $('#lead_status_tab').val(statusName);
//         $('#status_filter').val('').trigger('change');
//     } 
//     else {
//         $('#lead_status_tab').val('');
//         $('#status_filter').val(statusName).trigger('change');
//     }

//     $('#applyButton').click();
// }

function filterByStatusTab(status, element) {
    setActiveLeadTab(status);

    if (status === 'All') {
        localStorage.removeItem('lead_filters');
        clearLeadFilters();
        $('#resetFiltersBtn').hide();
        applyFilters({});
        return;
    }

    $('#lead_status_tab').val(status);

    const filters = {
        order: '',
        status: '',
        lead_status_tab: status,
        type: '',
        date_from: '',
        date_to: '',
        date_type: '',
        assign_type: '',
        selectedValue: '',
        lead_source: '',
        limit: leadPageSize
    };

    localStorage.setItem('lead_filters', JSON.stringify(filters));
    clearLeadFilters();
    $('#lead_status_tab').val(status);

    applyFilters(filters);
}
</script>
