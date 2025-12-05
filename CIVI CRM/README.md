# People Finder - CiviCRM Extension

A privacy-focused CiviCRM extension that allows users to search for friends from the past and connect with them without disclosing personal information.

## Features

### 1. User Authentication & Registration
- Users must already be in the CiviCRM database to register
- Email-based verification system
- Invitation links for administrators to send to users
- Secure token-based authentication

### 2. Privacy-Restricted Search
- Search only returns partial information (e.g., "Steve S, Michigan")
- Full contact details are only revealed after a connection is accepted
- No crawling allowed - only searches contacts already in the database
- Search logging for audit purposes

### 3. Connection System
- Users can send connection requests to people they find
- Connection requests include optional messages
- Recipients can accept or decline connections
- Full contact information is only shared after acceptance

## Installation

1. Copy this extension to your CiviCRM extensions directory:
   ```
   cp -r "CIVI CRM " /path/to/civicrm/extensions/com.example.peoplefinder
   ```

2. Install the extension via CiviCRM:
   - Go to Administer > System Settings > Extensions
   - Find "People Finder" and click Install

3. The extension will automatically:
   - Create necessary database tables
   - Register API endpoints
   - Create menu items

## Usage

### For End Users

1. **Registration**: 
   - Navigate to People Finder login page
   - Enter your email address (must be in database)
   - Check your email for verification link
   - Click the link to verify and access People Finder

2. **Search**:
   - Use the search form to find people by:
     - First name
     - Last name
     - State/Province
     - City
   - Results show partial information (e.g., "Steve S, Michigan")

3. **Connect**:
   - Click "Connect" on a search result
   - Optionally add a message
   - The person will receive a notification
   - If they accept, full contact information is shared

### For Administrators

1. **User Management**:
   - Access the admin page to view all registered users
   - Send invitation links to users
   - Manage user accounts

2. **API Access**:
   - All functionality is available via CiviCRM API3
   - See API documentation below

## API Endpoints

### PeopleFinder.search
Search for people with privacy restrictions.

**Parameters:**
- `first_name` (optional): First name to search
- `last_name` (optional): Last name to search
- `state_province` (optional): State/Province to search
- `city` (optional): City to search
- `limit` (optional): Results limit (default: 50)
- `offset` (optional): Results offset (default: 0)

**Returns:** Array of contacts with partial information

**Example:**
```php
$result = civicrm_api3('PeopleFinder', 'search', [
  'first_name' => 'Steve',
  'last_name' => 'Smith',
  'state_province' => 'Michigan',
]);
```

### PeopleFinder.register
Register a new user (must be in database).

**Parameters:**
- `email` (required): Email address

**Returns:** User ID and verification status

**Example:**
```php
$result = civicrm_api3('PeopleFinder', 'register', [
  'email' => 'user@example.com',
]);
```

### PeopleFinder.verify
Verify email with token.

**Parameters:**
- `token` (required): Verification token

**Returns:** User ID and verification status

### PeopleFinder.connect
Send a connection request.

**Parameters:**
- `contact_id` (required): Target contact ID
- `message` (optional): Message to include

**Returns:** Connection ID

**Example:**
```php
$result = civicrm_api3('PeopleFinder', 'connect', [
  'contact_id' => 123,
  'message' => 'Hi, remember me?',
]);
```

### PeopleFinder.getconnections
Get connection requests for current user.

**Parameters:**
- `status` (optional): Filter by status (pending, accepted, declined)

**Returns:** Array of connections

## Database Schema

### civicrm_peoplefinder_user
Stores registered users and their verification status.

### civicrm_peoplefinder_connection
Stores connection requests between users.

### civicrm_peoplefinder_search_log
Logs all searches for audit purposes.

## Security Features

1. **Authentication Required**: All searches require authenticated, verified users
2. **Privacy by Default**: Only partial information is returned in searches
3. **No Crawling**: Only searches contacts already in the database
4. **Connection-Based Sharing**: Full information only shared after mutual connection
5. **Search Logging**: All searches are logged for security auditing

## Requirements

- CiviCRM 5.0 or higher
- PHP 7.0 or higher
- MySQL/MariaDB

## Support

For issues or questions, please contact your CiviCRM administrator.

