# People Finder Extension - Features

## Core Features

### 1. User Registration & Authentication ✅
- **Email-based registration**: Users must already exist in CiviCRM database
- **Email verification**: Secure token-based verification system
- **Invitation system**: Administrators can send invitation links to users
- **Session management**: Secure login/logout functionality

### 2. Privacy-Restricted Search ✅
- **Partial information display**: Search results show format like "Steve S, Michigan"
  - First name: Full or initial
  - Last name: Full (for matching purposes)
  - Location: State/Province and City
- **No crawling protection**: Only searches contacts already in the database
- **Authentication required**: Only verified users can search
- **Search logging**: All searches are logged for audit purposes

### 3. Connection System ✅
- **Connection requests**: Users can send requests to people they find
- **Optional messages**: Include personal messages with connection requests
- **Privacy protection**: Full contact information only shared after acceptance
- **Status tracking**: Connections have status (pending, accepted, declined)
- **Email notifications**: Recipients are notified of connection requests

### 4. Administrative Features ✅
- **User management**: View all registered users
- **Invitation sending**: Send invitation links to users
- **User deletion**: Remove users from the system
- **Access control**: Admin features require administrator permissions

## Technical Implementation

### Database Tables
1. **civicrm_peoplefinder_user**: Stores user accounts and verification status
2. **civicrm_peoplefinder_connection**: Stores connection requests between users
3. **civicrm_peoplefinder_search_log**: Logs all searches for security auditing

### API Endpoints
- `PeopleFinder.search`: Search for people with privacy restrictions
- `PeopleFinder.register`: Register a new user
- `PeopleFinder.verify`: Verify email with token
- `PeopleFinder.connect`: Send connection request
- `PeopleFinder.getconnections`: Get connection requests

### Frontend Pages
- `/civicrm/peoplefinder`: Main search interface
- `/civicrm/peoplefinder/login`: Login/registration page
- `/civicrm/peoplefinder/verify`: Email verification page
- `/civicrm/peoplefinder/logout`: Logout handler
- `/civicrm/peoplefinder/admin`: Admin user management

## Privacy & Security Features

1. **Authentication Required**: All searches require authenticated, verified users
2. **Privacy by Default**: Only partial information returned in search results
3. **No Crawling**: System only searches existing contacts, prevents data harvesting
4. **Connection-Based Sharing**: Full information only shared after mutual connection
5. **Search Logging**: All searches logged for security auditing
6. **Token-Based Security**: Secure tokens for email verification and invitations

## User Flow

### Registration Flow
1. User visits login page
2. Enters email address (must be in database)
3. Receives verification email with token
4. Clicks verification link
5. Automatically logged in and redirected to search page

### Search Flow
1. Authenticated user enters search criteria
2. System searches CiviCRM contacts (only existing contacts)
3. Returns partial information (e.g., "Steve S, Michigan")
4. User can click "Connect" on a result
5. Sends connection request with optional message

### Connection Flow
1. User sends connection request
2. Target contact receives email notification (if registered)
3. Target can accept or decline
4. If accepted, full contact information is shared
5. Both users can now see full details

## Future Enhancement Ideas

- Connection history and messaging
- Advanced search filters (age range, graduation year, etc.)
- Privacy settings (opt-out of being searchable)
- Bulk invitation system
- Search analytics dashboard
- Integration with CiviCRM groups
- Export connection data

