# Google Sheets Integration for BUCUC Application System

This document explains how the Google Sheets integration works and how to set it up.

## Overview

When an application is accepted in `pending_applications.php`, the system will:
1. Update the member status in the database to 'Accepted'
2. Send a congratulations email to the member
3. **NEW: Automatically add the member data to your Google Sheet**
4. Log the operation for debugging purposes

## Files Added/Modified

### New Files:
- `Action/google_sheets_integration.php` - Main integration functions
- `config/google_sheets_config.php` - Configuration settings
- `test_google_sheets.php` - Test script to verify integration
- `logs/` - Directory for logging Google Sheets operations

### Modified Files:
- `Action/application_handler.php` - Added Google Sheets integration to acceptance workflow

## Google Sheet Columns

Your Google Sheet should have these exact column headers:
- **Name** - Full name of the member
- **ID** - University ID
- **G-Suite** - G-Suite email (falls back to regular email if G-Suite is empty)
- **Position** - Currently mapped to Department field
- **Facebook** - Facebook URL
- **Phone** - Phone number
- **FirstP** - First priority
- **SecondP** - Second priority

## Google Apps Script Setup

Your Google Apps Script web app should expect POST data with these field names and add them to your sheet. Here's a sample Google Apps Script code:

```javascript
function doPost(e) {
  try {
    // Get the active spreadsheet
    var sheet = SpreadsheetApp.getActiveSheet();
    
    // Parse JSON data from POST request
    var jsonData = JSON.parse(e.postData.contents);
    
    // Get the data from JSON
    var name = jsonData.Name || '';
    var id = jsonData.ID || '';
    var gsuite = jsonData['G-Suite'] || '';
    var position = jsonData.Position || '';
    var facebook = jsonData.Facebook || '';
    var phone = jsonData.Phone || '';
    var firstP = jsonData.FirstP || '';
    var secondP = jsonData.SecondP || '';
    
    // Add a new row with the data
    sheet.appendRow([name, id, gsuite, position, facebook, phone, firstP, secondP]);
    
    // Return success response
    return ContentService
      .createTextOutput(JSON.stringify({
        'success': true,
        'message': 'Data added successfully'
      }))
      .setMimeType(ContentService.MimeType.JSON);
      
  } catch (error) {
    // Return error response
    return ContentService
      .createTextOutput(JSON.stringify({
        'success': false,
        'message': 'Error: ' + error.toString()
      }))
      .setMimeType(ContentService.MimeType.JSON);
  }
}
```

## Configuration

Edit `config/google_sheets_config.php` to customize settings:

```php
// Your Google Apps Script Web App URL
define('GOOGLE_SHEETS_WEBAPP_URL', 'https://script.google.com/macros/s/YOUR_SCRIPT_ID/exec');

// Request timeout (seconds)
define('GOOGLE_SHEETS_TIMEOUT', 30);

// Enable/disable logging
define('GOOGLE_SHEETS_LOGGING', true);
```

## Testing

1. Run the test script by accessing `test_google_sheets.php` in your browser
2. This will send sample data to your Google Sheet
3. Check the results and logs to ensure everything is working

## Data Flow

When an admin accepts an application:

```
[Admin clicks Accept] 
    ↓
[Update database status to 'Accepted']
    ↓
[Send congratulations email]
    ↓
[Send data to Google Sheets via cURL POST]
    ↓
[Log the operation]
    ↓
[Return success/error message to admin]
```

## Logging

All Google Sheets operations are logged to `logs/google_sheets.log` with:
- Timestamp
- Action performed
- Data sent
- Response received

## Troubleshooting

### Common Issues:

1. **403/404 Errors**: Check that your Google Apps Script is deployed as a web app with proper permissions

2. **Timeout Errors**: Increase `GOOGLE_SHEETS_TIMEOUT` in config

3. **Data not appearing**: Check your Google Apps Script logs and verify column mappings

4. **Permission Issues**: Ensure your Google Apps Script has permission to edit the spreadsheet

### Debug Steps:

1. Check `logs/google_sheets.log` for detailed error messages
2. Run `test_google_sheets.php` to isolate issues
3. Verify your Google Apps Script web app URL
4. Test your Google Apps Script directly with a POST request

## Error Handling

The system is designed to continue working even if Google Sheets integration fails:
- Applications will still be accepted and emails sent
- Errors are logged but don't stop the process
- Admins receive informative error messages

## Security Notes

- The integration uses HTTPS for all requests
- SSL verification is disabled in development (re-enable for production)
- No sensitive data is logged (passwords, etc.)
- Rate limiting should be implemented in your Google Apps Script if needed

## Customization

To modify which data is sent to Google Sheets:
1. Edit the `$sheetData` array in `sendToGoogleSheets()` function
2. Update your Google Apps Script to handle the new fields
3. Modify your Google Sheet columns accordingly

## Support

If you encounter issues:
1. Check the logs in `logs/google_sheets.log`
2. Verify your Google Apps Script configuration
3. Test with the provided test script
4. Ensure your Google Sheet has the correct column headers
