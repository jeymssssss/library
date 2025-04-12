<?php
// Include the FPDF library
require('fpdf/fpdf.php');

// Create a PDF class that extends the FPDF class
class PDF extends FPDF
{
    // Header function
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Parent/Guardian Consent Form', 0, 1, 'C');
        $this->Ln(10);
    }

    // Footer function
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Content function to display the title
    function ChapterTitle($title)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 6, $title, 0, 1, 'L');
        $this->Ln(4);
    }

    // Function to display the body/content
    function ChapterBody($body)
    {
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 10, $body);
        $this->Ln();
    }
}

// Set the correct content-type for PDF output
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="parent_consent_form.pdf"');

// Create the PDF object
$pdf = new PDF();
$pdf->AddPage();

// Title for the consent form
$pdf->ChapterTitle('Parent/Guardian Consent for User Below 13 Years Old');

// Content for the consent form
$body = 'I, the undersigned, hereby give my consent for my child/ward to register with the library system and use its services.

Please provide the following details:

1. Child\'s Full Name: ________________________________________
2. Child\'s Age: _____________________________________________
3. Child\'s School ID (If Applicable): ___________________________
4. Parent/Guardian Name: _____________________________________
5. Parent/Guardian Contact Information: _______________________
6. Parent/Guardian Valid ID Number: __________________________
7. Signature of Parent/Guardian: ______________________________
8. Date: _____________________________________________________

By signing this form, I confirm that the information provided is accurate and I agree to the Terms and Conditions of the library system.';

// Add body content
$pdf->ChapterBody($body);

// Output the PDF (this will force the download as a PDF file)
$pdf->Output();
?>
