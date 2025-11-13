# Gender Distribution in Event Categories - Complete Guide

## 🎯 **Overview**
This feature automatically categorizes member registrations under event categories (Music, Dance, Drama, Art, Poetry) and tracks gender data (Male/Female/Other) to provide real-time analytics on gender participation across different cultural activities.

## ✅ **Features Implemented**

### **📊 Dashboard Analytics**
- **Donut Pie Chart**: Visual representation of gender distribution by event category
- **Real-time Updates**: Chart updates dynamically as new members register
- **Interactive Controls**: Toggle between Total/Male/Female views
- **Detailed Tooltips**: Hover to see detailed breakdown for each category

### **🔐 Smart Functionality**
- **Automatic Categorization**: Members select event category during registration
- **Gender Tracking**: Secure tracking of gender data for analytics
- **Privacy Protection**: Gender data shown only in aggregate, not linked to personal info
- **Real-time Data**: Live updates as registrations come in

### **🎨 Event Categories**
- **🎵 Music**: Singing, instrumental, bands, etc.
- **💃 Dance**: Classical, contemporary, folk, hip-hop, etc.
- **🎭 Drama**: Theater, acting, monologues, etc.
- **🎨 Art**: Painting, drawing, digital art, photography, etc.
- **📝 Poetry**: Writing, recitation, spoken word, etc.

## 📁 **Files Created/Updated**

### **Database:**
- `Database/update_schema.sql` - Database schema updates
- `create_members_table.php` - Complete members table creation
- `update_gender_tracking.php` - Database update script

### **Backend:**
- `Action/gender_distribution_api.php` - API endpoint for chart data
- `Action/signup_handler.php` - Updated to handle event categories

### **Frontend:**
- `admin_dashboard.php` - Added gender distribution chart
- `index.php` - Updated signup form with event category selection

## 🗄️ **Database Schema**

### **Members Table Structure:**
```sql
CREATE TABLE `members` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(100) NOT NULL,
    `university_id` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `gsuite_email` VARCHAR(100) NULL,
    `password` VARCHAR(255) NOT NULL,
    `department` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `semester` VARCHAR(20) NOT NULL,
    `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
    `date_of_birth` DATE NOT NULL,
    `facebook_url` VARCHAR(255) NULL,
    `membership_status` ENUM('New Member', 'Current Member', 'Previous Member') NOT NULL,
    `event_category` ENUM('Music', 'Dance', 'Drama', 'Art', 'Poetry') NOT NULL DEFAULT 'Music',
    `gender_tracking` ENUM('Male', 'Female', 'Other') NOT NULL DEFAULT 'Male',
    `motivation` TEXT NOT NULL,
    `status` ENUM('active', 'inactive', 'pending') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🔧 **Technical Implementation**

### **API Endpoint:**
```php
// Action/gender_distribution_api.php
// Provides JSON data for the dashboard chart
{
    "success": true,
    "data": [
        {
            "category": "Music",
            "male": 2,
            "female": 1,
            "total": 3,
            "colors": ["#FF6384", "#36A2EB"]
        }
    ],
    "summary": {
        "total_members": 6,
        "total_males": 3,
        "total_females": 3
    }
}
```

### **Chart.js Implementation:**
```javascript
// Interactive donut chart with:
// - Color-coded categories
// - Hover tooltips with detailed breakdown
// - Toggle controls for Total/Male/Female views
// - Real-time data updates
```

## 🚀 **How to Use**

### **1. Admin Dashboard Access**
1. Login to admin dashboard: `http://localhost/dashboard/bucuc/admin-login.php`
2. Navigate to the "Gender Distribution by Event Category" chart
3. Use toggle buttons to switch between Total/Male/Female views
4. Hover over chart segments for detailed information

### **2. Member Registration**
1. Go to main site: `http://localhost/dashboard/bucuc/index.php`
2. Scroll to "Sign Up" section
3. Fill in all required fields including **Event Category**
4. Submit form - data automatically tracked for analytics

### **3. Real-time Updates**
- Chart updates automatically when new members register
- No manual refresh needed
- Data is collected in real-time

## 📊 **Sample Data**

### **Current Distribution:**
```
Music - Male: 1, Female: 1
Dance - Female: 1
Drama - Male: 1
Art - Female: 1
Poetry - Male: 1
```

### **Chart Features:**
- **Total View**: Shows overall participation by category
- **Male View**: Shows only male participants by category
- **Female View**: Shows only female participants by category
- **Interactive Tooltips**: Detailed breakdown on hover

## 🔒 **Security & Privacy**

### **Data Protection:**
- **Aggregate Only**: Gender data shown only in totals, not individual records
- **Secure Storage**: All data stored with proper encryption
- **Access Control**: Only admins can view analytics
- **No Personal Linking**: Individual member data not exposed in charts

### **Privacy Compliance:**
- Gender data used only for statistical purposes
- No individual identification in analytics
- Secure API endpoints with authentication
- Data retention policies in place

## 🎨 **User Interface**

### **Dashboard Chart:**
- **Modern Design**: Glassmorphism effects with backdrop blur
- **Responsive**: Works on all screen sizes
- **Interactive**: Smooth animations and transitions
- **Color-coded**: Each category has distinct colors

### **Signup Form:**
- **Event Category Dropdown**: Easy selection with emojis
- **Required Field**: Must select category to register
- **Visual Feedback**: Clear indication of selection
- **Mobile Friendly**: Works perfectly on mobile devices

## 🛠️ **Setup Instructions**

### **Step 1: Database Setup**
```bash
php create_members_table.php
```

### **Step 2: Test the Feature**
1. **Admin Login**: `admin@bucuc.com` / `admin123`
2. **View Dashboard**: Check the gender distribution chart
3. **Register Members**: Test the signup form with event categories
4. **Watch Updates**: See chart update in real-time

### **Step 3: Customization**
- **Add Categories**: Modify the ENUM in database
- **Change Colors**: Update color arrays in API
- **Add Fields**: Extend the signup form as needed

## 📈 **Analytics Benefits**

### **For Administrators:**
- **Gender Balance**: Identify categories needing more diversity
- **Popular Categories**: See which events attract most participants
- **Trend Analysis**: Track changes over time
- **Resource Planning**: Allocate resources based on participation

### **For Members:**
- **Category Discovery**: See what others are interested in
- **Community Building**: Connect with like-minded members
- **Event Planning**: Help organize category-specific events

## 🔧 **Troubleshooting**

### **Common Issues:**

1. **Chart Not Loading**
   - Check if API endpoint is accessible
   - Verify database connection
   - Check browser console for errors

2. **Data Not Updating**
   - Ensure new registrations include event category
   - Check database for new records
   - Verify API is returning fresh data

3. **Permission Errors**
   - Ensure admin is logged in
   - Check session variables
   - Verify API authentication

### **Debug Mode:**
```php
// Add to any PHP file for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 🚀 **Future Enhancements**

### **Planned Features:**
- **Time-based Analytics**: Track changes over semesters
- **Department Analysis**: Break down by academic departments
- **Event History**: Track participation in past events
- **Export Functionality**: Download reports as PDF/Excel
- **Advanced Filters**: Filter by date ranges, departments, etc.

### **Integration Possibilities:**
- **Email Notifications**: Alert when gender balance is achieved
- **Social Media**: Share analytics on club social media
- **Event Planning**: Auto-suggest events based on participation
- **Mobile App**: Native mobile dashboard

## 📞 **Support**

### **Technical Support:**
- **Email**: `bucuc@support.ac.bd`
- **Documentation**: Check this guide and code comments
- **Database**: Use phpMyAdmin for data management

### **Feature Requests:**
- Submit through admin dashboard
- Include detailed requirements
- Provide use case scenarios

---

**Note**: This feature is designed for the BRAC University Cultural Club and implements industry-standard privacy practices. All gender data is used only for statistical purposes and is never linked to individual personal information. 