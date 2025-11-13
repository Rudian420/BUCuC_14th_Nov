// Dropdown Diagnostic Script
// Add this to your browser console to debug the dropdown issue

console.log('=== DROPDOWN DIAGNOSTIC TEST ===');

// Check if elements exist
const firstDept = document.getElementById('signup-first-dept');
const secondDept = document.getElementById('signup-second-dept');

console.log('First department dropdown:', firstDept ? 'FOUND' : 'NOT FOUND');
console.log('Second department dropdown:', secondDept ? 'FOUND' : 'NOT FOUND');

if (firstDept) {
    console.log('First dropdown options count:', firstDept.options.length);
    console.log('First dropdown HTML:', firstDept.outerHTML.substring(0, 200) + '...');
    
    for (let i = 0; i < firstDept.options.length; i++) {
        console.log(`  Option ${i}: "${firstDept.options[i].value}" - "${firstDept.options[i].text}"`);
    }
}

if (secondDept) {
    console.log('Second dropdown options count:', secondDept.options.length);
    console.log('Second dropdown HTML:', secondDept.outerHTML.substring(0, 200) + '...');
    
    for (let i = 0; i < secondDept.options.length; i++) {
        console.log(`  Option ${i}: "${secondDept.options[i].value}" - "${secondDept.options[i].text}"`);
    }
}

// Check if the signup form tab is visible
const signupTab = document.getElementById('nav-SignupForm');
const signupTabPane = document.getElementById('nav-SignupForm');
console.log('Signup tab pane:', signupTabPane ? 'FOUND' : 'NOT FOUND');
if (signupTabPane) {
    console.log('Signup tab pane classes:', signupTabPane.className);
    console.log('Signup tab pane display:', window.getComputedStyle(signupTabPane).display);
}

// Check DOM ready state
console.log('Document ready state:', document.readyState);

// Test manual initialization
console.log('Attempting manual dropdown initialization...');
try {
    if (firstDept && secondDept) {
        // Department options
        const departmentOptions = [
            { value: 'Admin', text: 'Admin' },
            { value: 'PR', text: 'PR - Public Relations and Editorial' },
            { value: 'HR', text: 'HR - Human Resources' },
            { value: 'EM', text: 'EM - Event Management and Logistics' },
            { value: 'Creative', text: 'Creative' },
            { value: 'Performance', text: 'Performance' },
            { value: 'RD', text: 'R&D - Research and Development' },
            { value: 'MIAP', text: 'MIAP - Marketing IT Archive & Photography' },
            { value: 'Finance', text: 'Finance' }
        ];
        
        // Clear and populate first dropdown
        firstDept.innerHTML = '<option value="">Select Department</option>';
        departmentOptions.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.value;
            option.textContent = dept.text;
            firstDept.appendChild(option);
        });
        
        // Clear and populate second dropdown
        secondDept.innerHTML = '<option value="">Select Department</option>';
        departmentOptions.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.value;
            option.textContent = dept.text;
            secondDept.appendChild(option);
        });
        
        console.log('✓ Manual initialization SUCCESS!');
        console.log('First dropdown now has', firstDept.options.length, 'options');
        console.log('Second dropdown now has', secondDept.options.length, 'options');
    } else {
        console.log('✗ Cannot initialize - dropdown elements not found');
    }
} catch (error) {
    console.error('Manual initialization FAILED:', error);
}

console.log('=== DIAGNOSTIC COMPLETE ===');
