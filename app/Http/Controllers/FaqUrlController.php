<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoyType;
use App\Models\Sample;
use Illuminate\Http\Request;
use App\Models\SampleCategory;
use Illuminate\Tests\Integration\Database\EloquentHasManyThroughTest\Category;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\FaqUrl;
use App\Models\Faq;
use Validator;// Ensure this package is installed and configured

class FaqUrlController extends Controller
{

    function generateFaqSchema(array $faqEntries)
    {
        $mainEntity = array_map(function ($entry) {
            return [
                "@type" => "Question",
                "name" => $entry['question'],
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => $entry['answer']
                ]
            ];
        }, $faqEntries);

        return json_encode([
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => $mainEntity
        ]);
    }
    public function index()
    {
        $data = FaqUrl::all();
        return view('faqs.faqurl', compact('data'));
    }

    public function FaqnameStore(Request $req)
    {
        $req->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'meta-title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $type = new FaqUrl();

        try {
            $type->name = $req->input('name');
            $type->title = $req->input('title');
            $type->meta_title = $req->input('meta-title');
            $type->description = $req->input('description');
            $type->slug = Str::slug($req->input('name'));
            $type->save();
            return redirect()->back()->with('success', 'Data submitted successfully.');
        } catch (\Exception $e) {
            // Log the error and return an error message
            \Log::error("Error submitting data: {$e->getMessage()}");
            return redirect()->back()->with('error', 'An error occurred while submitting the data.');
        }
    }
    public function update($id, Request $req)
    {
        // Validate the incoming request
        $req->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'meta-title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);


        // Find the FAQ URL by ID
        $faqUrl = FaqUrl::findOrFail($id);
        // Update the FAQ URL record with new data
        $faqUrl->name = $req->input('name');
        $faqUrl->title = $req->input('title');
        $faqUrl->meta_title = $req->input('meta-title');
        $faqUrl->description = $req->input('description');
        $faqUrl->slug = Str::slug($req->input('name'));

        // Save the updated record
        $faqUrl->save();

        // Redirect or return a response (e.g., to the updated FAQ page)
        return redirect()->back()->with('success', 'Data Updated successfully.');
    }

    public function faqUrlDelete($id)
    {
        $faqUrl = FaqUrl::findOrFail($id);
        $faqUrl->delete();
        return redirect()->back()->with('success', 'Data Deleted successfully.');

    }



    public function storeFAQs(Request $request)
    {
        try {
            // Validate incoming data
            $request->validate([
                'faqs' => 'required|array',
                'faqs.*.title_id' => 'required', // Ensure title_id exists in the faqurls table
                'faqs.*.question' => 'required|string',
                'faqs.*.answer' => 'required|string',
            ]);
            $faqData = array_map(function ($faq) {
                return [
                    'faqurl_id' => $faq['title_id'],
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                ];
            }, $request->faqs);

            Faq::insert($faqData);

            return response()->json([
                'success' => true,
                'message' => 'FAQs saved successfully!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save FAQs: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function getfaqBySlug($slug)
    {
        $faq = FaqUrl::with('faqs')->where('slug', $slug)->first();
        if (!$faq) {
            return redirect()->back()->with('error', 'FAQ not found');
        }
        $data = [
            'title' => $faq->meta_title,
            'description' => $faq->description,
            'Faqschema' => $this->generateFaqSchema($faq->faqs->toArray()), // Generate the schema from FAQs
        ];
        return view('faqs.maths-assignment-faqs', compact('faq', 'data'));
    }
    public function faqs()
    {
        $data = [
            'Faqschema' => $this->generateFaqSchema([
                [
                    "question" => "How does Assignment In Need work?",
                    "answer" => "Our approach is simple and easy to work with for students. Submit your assignment details through our website, make a safe payment, and our experts will deliver quality assignments right before your deadline."
                ],
                [
                    "question" => "Why should I consider Assignment In Need?",
                    "answer" => "Assignment In Need offers plagiarism-free original work completed by a PhD expert. We have a money refund in case you are not satisfied."
                ],
                [
                    "question" => "How is Assignment In Need any better than others?",
                    "answer" => "Our qualified experts, personalized support, on-time delivery, and affordable price set us apart in the market."
                ],
                [
                    "question" => "How long is Assignment In Need in Service?",
                    "answer" => "We have been rendering high-quality academic writing for 7+ years which helped, 45000+ students in achieving their academic goals."
                ],
                [
                    "question" => "Is there customer support that operates 24/7?",
                    "answer" => "Yes, our customer support team is available 24/7 round-the-clock to assist you with any type of assignment and inquiries or concerns."
                ],
                [
                    "question" => "Which subjects and topics do you tutor?",
                    "answer" => "We offer support across hundreds of subjects, from Business to Engineering, Literature to almost all subjects. If your topic isn't listed, send us a message—we want to help!"
                ],
                [
                    "question" => "Do your writers cover any subject?",
                    "answer" => "Yes! From every academic discipline imaginable, we have experts ready to ace your assignments in any of these subjects."
                ],
                [
                    "question" => "Do you cover specialized subjects?",
                    "answer" => "Absolutely! Our team consists of expert writers in niche subjects, so never hesitate to ask if you need help with anything."
                ],
                [
                    "question" => "Can I get help with multiple subjects at once?",
                    "answer" => "Yes! Whether you need help in many subjects or related areas, our writers can handle difficult assignments with start."
                ],
                [
                    "question" => "Can I request a tutor for a specific subject?",
                    "answer" => "Yes, if you need assistance and help in an any types of subject, we can hire an expert specializing in the same field to tutor you."
                ],
                [
                    "question" => "Is my personal data safe?",
                    "answer" => "Of course! We respect your privacy, and all personal information is kept confidential."
                ],
                [
                    "question" => "Can anybody view my identity?",
                    "answer" => "No, because your identity is fully secured. We guarantee that all your personal details are safe and kept private."
                ],
                [
                    "question" => "How do you protect my payment details?",
                    "answer" => "We use secure encryption to safeguard your payment information during the transaction process"
                ],
                [
                    "question" => "Will my tutor or writer have access to my personal information?",
                    "answer" => "No, your personal information is not revealed to the writer or tutor unless it is absolutely necessary for the assignment."
                ],
                [
                    "question" => "Can I request a tutor for a specific subject?",
                    "answer" => "Yes, if you need assistance and help in an any types of subject, we can hire an expert specializing in the same field to tutor you."
                ],
                [
                    "question" => "May I communicate directly with the expert working on my paper?",
                    "answer" => "Yes, we offer one-on-one communication with our writers to ensure that your assignments are meets academic standard efficiently."
                ],
                [
                    "question" => "How do I reach out to my writer?",
                    "answer" => "You are allowed to reach out to our writer via our website's communication tool for easy communication during your assignments process and after the completing assignments too."
                ],
                [
                    "question" => "Can I request a particular writer to do my assignments?",
                    "answer" => "If you have previously worked with a writer and wish to continue working with the same writer, you can request them again for your future assignments"
                ],
                [
                    "question" => "Can I request my writer to give me progress updates?",
                    "answer" => "Yes, you can always request progress updates to ensure that the work is being done according to your academic requirements."
                ],
                [
                    "question" => "How do I ensure my tutor understands my requirements clearly?",
                    "answer" => "When submitting your assignment or project, please make sure to provide as much detail as possible. You may also discuss your requirements directly with the tutor before they begin working"
                ],
                [
                    "question" => "What happens if I am not satisfied with the work?",
                    "answer" => "We allow unlimited revisions until you are satisfied. In case your expectations are not met after revisions, our refund policy will have your back."
                ],
                [
                    "question" => "Do I get corrections if my solution is incorrect?",
                    "answer" => "Absolutely! If any part of your assignment needs correction, we will address it promptly through revisions."
                ],
                [
                    "question" => "How many revisions are included?",
                    "answer" => "We provide unlimited revisions until you are satisfied with the quality of the work delivered."
                ],
                [
                    "question" => "Will you modify my assignment if I want to change something after submitting it?",
                    "answer" => "Yes, we will change it according to your request so that the final product meets your needs."
                ],
                [
                    "question" => "Do you guarantee meeting deadlines?",
                    "answer" => "Yes, meeting deadlines is our priority. We also provide urgent help for last-minute needs."
                ],
                [
                    "question" => "If I need my paper urgently?",
                    "answer" => "We offer urgent assignment help with quick turnaround times—just let us know your deadline, and we'll make it happen."
                ],
                [
                    "question" => "Can you deliver an assignment in 24 hours or less?",
                    "answer" => "Yes, we can deliver assignments within 24 hours, depending on the complexity of the work."
                ],
                [
                    "question" => "What happens if I miss my deadline or do not submit the order on time?",
                    "answer" => "If you are unable to deliver the details on time, please contact us and we will do our best to accommodate your request."
                ],
                [
                    "question" => "How much do your services cost?",
                    "answer" => "Our prices are competitive and vary depending on your assignment's complexity and deadline. We also offer a discount to make our service more affordable."
                ],
                [
                    "question" => "How do I pay?",
                    "answer" => "You can safely pay through various modes, including credit/debit cards and online wallets."
                ],
                [
                    "question" => "What modes of payment do we accept?",
                    "answer" => "We accept credit/debit card payments, PayPal, and other forms of online payment for your ease."
                ],
                [
                    "question" => "Can I pay in easy installments?",
                    "answer" => "We offer flexible payment options for you. Call us to know more in case you want to pay through instalment."
                ],
                [
                    "question" => "Do you have student discounts?",
                    "answer" => "Yes! We have student discounts so that our services can be affordable. You also can use coupons to save more"
                ],
                [
                    "question" => "Is there scope for negotiation of price while ordering in bulk?",
                    "answer" => "Sure, if you have bulk assignments, we can definitely discuss discounts or a personalized plan for the bulk."
                ],
                [
                    "question" => "Does your company offer plagiarism-free assignments?",
                    "answer" => "Absolutely! It uses advanced tools to ensure the assignments are 100% free of plagiarism."
                ],

                [
                    "question" => "Who judges the quality standard of the work I ordered?",
                    "answer" => "Our expert writers are experienced in maintaining high-quality standards. You'll receive work that meets or exceeds your expectations."
                ],

                [
                    "question" => "How do I know that your work will meet your guarantees?",
                    "answer" => "We ensure plagiarism-free, original work based on your instructions. You may also request revisions in case you need them."
                ],
                [
                    "question" => "How do you respond to plagiarism allegations?",
                    "answer" => "Plagiarism is something that we take very seriously and ensure 100% original work. In case of some sort of plagiarism is detected, we will rectify that and give a refund too."
                ],

                [
                    "question" => "What is your refund policy?",
                    "answer" => "If we cannot deliver the promised quality, or if we fail to complete it within the deadline, then you can ask for a refund of your order based on our guarantee."
                ],

                [
                    "question" => "Is there a cancellation of the order?",
                    "answer" => "Yes, cancellations occur when the project is being worked on or has been completed. To get details, contact our representatives."
                ],

                [
                    "question" => "But the work is done on time, but I could not receive it. Should I get a refund for it?",
                    "answer" => "We shall adjust this delivery issue immediately and return your money back."
                ],

                [
                    "question" => "But what shall I do when I demand for a refund?",
                    "answer" => "If you're not satisfied with the work, contact our support team and we will ensure that the refund process is done as fast as possible."
                ],
                [
                    "question" => "How do I sign up?",
                    "answer" => "Signing up is quite easy! Click on the “Sign Up” link and fill in the requested details to get started with our services immediately."
                ],

                [
                    "question" => "What if I forgot my password?",
                    "answer" => "You can reset it right away by clicking “Forgot Password” on the sign-in page and follow through the instructions received via email for resetting."
                ],

                [
                    "question" => "How can I change my account details?",
                    "answer" => "You can change your account settings, including email, password, and other details, by accessing your account dashboard."
                ],

                [
                    "question" => "How can I change my EMAIL, PASSWORD & USERNAME?",
                    "answer" => "Login to your account and update your email, password, or username via your account settings."
                ],

                [
                    "question" => "Can I delete my account?",
                    "answer" => "Of course, yes. You can delete the account any time by contacting our support team."
                ],
                [
                    "question" => "Will I receive my assignment in my mailbox?",
                    "answer" => "Yes, your assigned work will be sent through email before the deadline is set."
                ],

                [
                    "question" => "What is the time for receiving the work?",
                    "answer" => "We make it sure to submit assignments timely according to the agreed date of submission. Your work is surely going to reach you by then."
                ],
                [
                    "question" => "How would I know that your service will promote plagiarism?",
                    "answer" => "We strictly adhere to academic integrity, ensuring that all assignments are 100% original and plagiarism-free."
                ],

                [
                    "question" => "How can I track my order status?",
                    "answer" => "You can track your order status by logging into your account and accessing the project details at any time."
                ],

                [
                    "question" => "Can I delete my account?",
                    "answer" => "Of course, yes. You can delete the account any time by contacting our support team."
                ],

            ]),
        ];
        $data['title'] = 'FAQs - Clear Answers to Student Queries @ Assignment In Need';
        $data['description'] = 'Find quick answers to common questions about our assignment writing services. Learn about pricing, deadlines, subjects, and guarantees at Assignment In Need.';
        return view('faqs.faq' , compact('data'));
    }


  
    

}



