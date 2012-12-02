# 0.4.0 - 12/02/2012

- Upgraded to support Kohana 3.3
- Renamed class files and directories to support PSR-0
- Resolved pass by reference issue (now testing in strict mode)
- All tests pass: OK (17 tests, 86 assertions)

# 0.3.2 - 09/29/2012

- Resolved issue #4 (https://github.com/morgan/kohana-deputy/issues/4). In `Deputy_Role`, denying 
a child would also deny any parents in the URI. Now explicitly looking for URI definition before 
checking for wildcard.
- Added unit test coverage for aforementioned defect.
- Implemented result caching for `Deputy_Role::is_allowed` and `Deputy_Role::is_denied`.
- All tests pass: OK (14 tests, 83 assertions)

# 0.3.1 - 07/30/2012

- Resolved `Deputy_Resource::__construct` issue in deriving segment.
- Added unit test coverage for defect.
- All tests pass: OK (12 tests, 81 assertions)

# 0.3.0 - 12/04/2011

- Renamed `Deputy::get_title` to `Deputy::title` and made a getter/setter
- Renamed `Deputy::get_uri` to `Deputy::uri` and made a getter/setter
- Refactored `Deputy::is_visible` to a getter/setter
- Added `Deputy_Resource::segment` for individual Resource URI segment
- Added `Deputy_Resource::meta` for custom meta data.
- Updated User Guide documentation
- Added `Kohana_Deputy_Resource_Test::test_meta` and updated tests to use new naming.
- All tests pass: OK (11 tests, 73 assertions)

# 0.2.0 - 10/23/2011

- Upgraded to support Kohana 3.2
- Minor changes to syntax
- Updated User Guide to reflect changes to Kohana config
- Dropped demo module example

# 0.1.0 - 06/01/2011

- Initial release of Deputy
- Role and Resource support
- User Guide documentation
- Unit Test coverage
