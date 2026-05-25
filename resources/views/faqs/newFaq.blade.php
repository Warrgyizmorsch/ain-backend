@extends('layouts.app')

@section('content')

<style>
    .btn-danger {
        margin: 5px;
    }
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="container-xxl">

        <!-- Toolbar -->
        <div class="toolbar mb-5" id="kt_toolbar">
            <div class="container-fluid d-flex flex-stack" id="kt_toolbar_container">
                <div class="page-title d-flex align-items-center flex-wrap me-3">
                    <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Add New FAQs</h1>
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">Assignment In Need</small>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- FAQs Form -->
        <div class="row g-5 g-xl-12">
            <div class="col-xl-12">
                <div class="card card-xxl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">All Categories</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <form id="faqForm">
                            @csrf

                            <!-- Title Selection -->
                            <div class="mt-4 mb-4">
                                <label for="title" class="form-label fw-bold">Title</label>
                                <select id="title" class="form-select" name="title">
                                    <option value="" selected disabled>Select a title</option>
                                    @foreach ($data as $obj)
                                        <option value="{{$obj->id}}">{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Question Input -->
                            <div class="mt-4 mb-4">
                                <label for="question" class="form-label fw-bold">Question</label>
                                <input type="text" id="question" class="form-control" name="question">
                            </div>

                            <!-- Answer Input -->
                            <div class="mt-4 mb-4">
                                <label for="answer" class="form-label fw-bold">Answer</label>
                                <input type="text" id="answer" class="form-control" name="answer">
                            </div>

                            <!-- Add Q&A Button -->
                            <div class="mt-4">
                                <button type="button" class="btn btn-primary" id="addRowBtn">Add Q&A</button>
                            </div>
                        </form>

                        <!-- FAQs Table -->
                        <table id="qaTable" class="table table-striped table-hover mt-4">
                            <thead class="table-dark">
                                <tr>
                                    <th class="px-3 py-2">Title</th>
                                    <th class="px-3 py-2">Question</th>
                                    <th class="px-3 py-2">Answer</th>
                                    <th class="px-3 py-2 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be added dynamically -->
                            </tbody>
                        </table>

                        <!-- Submit Button -->
                        <div class="button-group text-end">
                            <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
                        </div>

                        <!-- JavaScript -->
                        <script>
                            // Add Q&A Row
                            document.getElementById("addRowBtn").addEventListener("click", function () {
                                const titleSelect = document.getElementById("title");
                                const questionInput = document.getElementById("question");
                                const answerInput = document.getElementById("answer");

                                const title = titleSelect.options[titleSelect.selectedIndex].text;
                                const titleId = titleSelect.value;
                                const question = questionInput.value.trim();
                                const answer = answerInput.value.trim();

                                if (!titleId || question === "" || answer === "") {
                                    alert("Please fill in all fields.");
                                    return;
                                }

                                const tableBody = document.querySelector("#qaTable tbody");
                                const newRow = document.createElement("tr");

                                // Title Cell
                                const titleCell = document.createElement("td");
                                titleCell.textContent = title;
                                newRow.appendChild(titleCell);

                                // Hidden Title ID
                                const titleIdCell = document.createElement("input");
                                titleIdCell.type = "hidden";
                                titleIdCell.value = titleId;
                                newRow.appendChild(titleIdCell);

                                // Question Cell
                                const questionCell = document.createElement("td");
                                questionCell.textContent = question;
                                newRow.appendChild(questionCell);

                                // Answer Cell
                                const answerCell = document.createElement("td");
                                answerCell.textContent = answer;
                                newRow.appendChild(answerCell);

                                // Actions Cell
                                const actionCell = document.createElement("td");
                                actionCell.classList.add("text-end");

                                const deleteButton = document.createElement("button");
                                deleteButton.innerHTML = `<i class="bi bi-trash-fill"></i>`;
                                deleteButton.classList.add("btn", "btn-danger", "btn-sm");
                                deleteButton.addEventListener("click", function () {
                                    tableBody.removeChild(newRow);
                                });
                                actionCell.appendChild(deleteButton);

                                newRow.appendChild(actionCell);
                                tableBody.appendChild(newRow);

                                // Clear Inputs
                                questionInput.value = "";
                                answerInput.value = "";
                            });

                            // Submit All FAQs
                            document.getElementById("submitBtn").addEventListener("click", function () {
                                const rows = document.querySelectorAll("#qaTable tbody tr");
                                const faqs = [];

                                rows.forEach(row => {
                                    const cells = row.querySelectorAll("td");
                                    const titleId = row.querySelector("input").value;
                                    const question = cells[1].textContent.trim();
                                    const answer = cells[2].textContent.trim();

                                    faqs.push({ title_id: titleId, question: question, answer: answer });
                                });

                                // Send AJAX Request
                                fetch("{{ route('faqs.store') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    },
                                    body: JSON.stringify({ faqs: faqs })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert("FAQs submitted successfully!");
                                        location.reload();
                                    } else {
                                        alert("Error submitting FAQs. Please try again.");
                                    }
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
