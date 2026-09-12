@forelse ($leads as $lead)
    <tr>
        <td class='text-center'>
            {{ $loop->iteration }}
        </td>
        <td class="icon-container my-auto d-flex">
            @if($lead->flag == '1')
                <div class="form-check form-check-sm form-check-custom form-check-solid m-5">
                    <input onchange="checkedLead(this, {{ $lead->id }})"
                        class="form-check-input widget-13-check" type="checkbox" checked value="1">
                </div>
            @else
                <div class="form-check form-check-sm form-check-custom form-check-solid m-5">
                    <input onchange="checkedLead(this, {{ $lead->id }})"
                        class="form-check-input widget-13-check" type="checkbox" value="1">
                </div>
            @endif

            <div class="form-check form-switch my-auto">
                <input class="form-check-input" type="checkbox" id="{{ $lead->id }}"
                    role="switch" checked onchange="handleChange(this, {{ $lead->id }})">
            </div>

            <a href="#" data-kt-drawer-toggle="#kt_drawer_chat"
                id="kt_drawer_chat_toggle{{ $lead->id }}"
                class="btn btn-icon btn-bg-warning btn-active-color-light btn-sm me-1">
                Call
            </a>
            @include('leads.section.call-lead')

            <a href="/leadedit.{{$lead->id}}" target="_blank"
                class="btn btn-icon btn-bg-secondary btn-active-color-primary btn-sm me-1">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3"
                            d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                            fill="black"></path>
                        <path
                            d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                            fill="black"></path>
                    </svg>
                </span>
            </a>

            <a href="javascript:void(0);" id="clickToCallBtn{{$lead->id}}"
                class="btn btn-icon btn-bg-success btn-active-color-light btn-sm me-1">
                <span class="svg-icon svg-icon-3">
                    <li class="fa fa-phone fa-lg"></li>
                </span>
            </a>

            <form action="{{ url('convertleads/' . $lead->id) }}" method="post">
                @csrf
                <button type="submit"
                    class="btn btn-icon btn-bg-secondary btn-active-color-light btn-sm me-1">
                    <span class="svg-icon svg-icon-3">C</span>
                </button>
            </form>

            <a href="#" id="clickToDownload{{$lead->order_id}}"
                class="btn btn-icon btn-bg-danger btn-active-color-dark btn-sm me-1 download-btn{{$lead->id}}"
                onclick="downloadFiles(this)">
                <span class="svg-icon svg-icon-3">
                    <i class="fa fa-download fa-lg"></i>
                </span>
            </a>
        </td>

        <td class="text-center">
            <div class="d-inline-flex align-items-center justify-content-center">
                @if ($lead['frontendorder'] == '1')
                    <span class="badge badge-light-primary fs-7 fw-bold">{{ $lead->order_id }}</span>
                @else
                    <span>{{ $lead->order_id }}</span>
                @endif
                @if(!empty($lead->order_id))
                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0" style="width: 20px; height: 20px;" title="Copy Order ID" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $lead->order_id }}', 'Order ID copied!');">
                        <i class="fa fa-clone fs-8 text-muted"></i>
                    </button>
                @endif
            </div>

            <br>
            @if ($lead['resit'] == 'on')
                <span class="badge badge-light-danger fs-7 fw-bold">Resit Work</span>
            @endif
            @if ($lead['service_type'] == 'First Class Work')
                <span class="badge badge-light-info fs-7 fw-bold">First Class Work</span>
            @endif
        </td>

        <td>
            @if($lead->user && $lead->user->name)
                <div class="fw-bold">{{ $lead->user->name }}</div>
            @elseif(!empty($lead->user_name))
                <div class="fw-bold">{{ $lead->user_name }}</div>
            @endif

            @php
                $rawEmail = $lead->user->email ?? $lead->email ?? null;
                $displayEmail = $rawEmail ? mask_email_for_display($rawEmail) : null;
                $leadCountry = $lead->user->countrycode ?? $lead->countrycode ?? '';
                $cleanCC = preg_replace('/\D+/', '', (string)$leadCountry);
                $rawMobile = $lead->user->mobile_no ?? $lead->mobile ?? null;
                $displayMobile = $rawMobile ? mask_mobile_only($leadCountry, $rawMobile) : null;
            @endphp

            @if($displayEmail)
                <div class="d-inline-flex align-items-center my-1">
                    <span class="text-gray-600 fs-8 text-break">{{ $displayEmail }}</span>
                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-1 p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Email" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $displayEmail }}', 'Email copied!');">
                        <i class="fa fa-clone fs-8 text-muted"></i>
                    </button>
                </div>
                <br>
            @endif

            @if($displayMobile)
                <div class="d-inline-flex align-items-center gap-1 my-1">
                    @if(!empty($cleanCC))
                        <span class="badge badge-light-primary fs-8 fw-bold">+{{ $cleanCC }}</span>
                    @endif
                    <span class="badge badge-light-danger fs-7 fw-bold">{{ $displayMobile }}</span>
                    <button type="button" class="btn btn-icon btn-sm btn-active-light-danger p-0 flex-shrink-0" style="width: 18px; height: 18px;" title="Copy Mobile" onclick="event.stopPropagation(); crmCopyToClipboard('{{ $displayMobile }}', 'Mobile number copied!');">
                        <i class="fa fa-clone fs-8 text-danger"></i>
                    </button>
                </div>
                <br>
            @endif

            @php
                $leadWAPhone = preg_replace('/\D+/', '', (string)($cleanCC . ($rawMobile ?? '')));
                $leadRawEmail = $rawEmail ?? '';
                $leadEmailUrl = !empty($leadRawEmail) ? route('emails.index', ['search' => $leadRawEmail]) : route('emails.index');
                $leadWhatsAppUrl = !empty($leadWAPhone) ? route('whatsapp.chat', ['phone' => $leadWAPhone]) : route('whatsapp.chat');
            @endphp

            {{-- Direct Contact Actions: WhatsApp & Email --}}
            <div class="d-inline-flex align-items-center justify-content-center gap-2 my-1">
                <a href="{{ $leadWhatsAppUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-wa" title="WhatsApp: {{ $leadWAPhone ?: 'Open Chat' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 16 16">
                        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.364 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.707 2.002.806 2.134c.098.133 1.392 2.123 3.372 2.978.471.204.838.326 1.124.418.473.15.905.129 1.246.078.38-.058 1.17-.479 1.338-.943.166-.464.166-.862.116-.944-.049-.082-.182-.133-.38-.232"/>
                    </svg>
                </a>
                <a href="{{ $leadEmailUrl }}" target="_blank" class="btn btn-icon btn-sm crm-btn-email" title="Email: {{ $leadRawEmail ?: 'Open Emails' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16">
                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
                    </svg>
                </a>
            </div>
            <br>

            @if(($lead->user && $lead->user->verified == 1) || (!empty($lead->is_verified) && $lead->is_verified == 1))
                <span class="badge badge-light-success fs-8 fw-bold">
                    Verified
                </span>
            @else
                <span class="badge badge-light-warning fs-8 fw-bold">
                    Unverified
                </span>
            @endif
        </td>

        <td>{{ \Carbon\Carbon::parse($lead->create_at)->format('d M Y') }}</td>
        <td>
            {{ $lead->project_title }}
            <br>
            @if ($lead->semester)
                Semester : {{ $lead->semester }}
            @endif

            <br>
            @if ($lead['tech'] == 'on')
                <span class="badge badge-light-success fs-7 fw-bold">Technical Work</span>
            @endif

            @if ($lead['module_code'] != '')
                <span class="badge badge-light-danger fs-7 fw-bold">{{ $lead['module_code'] }}</span>
            @endif
        </td>
        <td>{{ $lead->pages }}</td>
        <td>{{ $lead->price }}</td>
        <td>
            {{ $lead->deadline }}
            @if($lead->delivery_time)
                <span class="badge badge-light-info fs-7 fw-bold">({{ $lead->delivery_time }})</span>
            @endif

            @if($lead->draft_required == 'Yes')
                <span class="badge badge-light-success fs-7 fw-bold">{{ $lead->draft_date }}</span>
                <br>
                <span class="badge badge-light-success fs-7 fw-bold">{{ $lead->draft_time }}</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center py-5 text-muted">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <i class="fa fa-search fs-2 mb-2 text-muted opacity-50"></i>
                <span class="fw-bold">No matching leads found</span>
                <span class="fs-8 text-muted">Try adjusting your filters or search terms.</span>
            </div>
        </td>
    </tr>
@endforelse
