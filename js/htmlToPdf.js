function generatePDF(){
    const element=document.getElementById('htmlContent');
    var opt = {
        filename:     'TableData.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 4 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        
      };
       
      // New Promise-based usage:
      html2pdf(element, opt);
    // html2pdf(excess);
    console.log('Save');
    return true;
}