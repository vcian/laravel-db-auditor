# Changelog

All notable changes to `laravel-db-auditor` will be documented in this file

## v2.2.0 - 2024-08-02

### What's Changed

* Integrated Postgresql. Code formatting and resolved minor bugs by @ruchit288 in https://github.com/vcian/laravel-db-auditor/pull/63
* Now you can audit the PostgreSQL database. Note- Add constraint feature is not supported at this moment for PostgreSQL.
* Update content and details in readme and composer files. by @ruchit288 in https://github.com/vcian/laravel-db-auditor/pull/64

**Full Changelog**: https://github.com/vcian/laravel-db-auditor/compare/v2.1.0...v2.2.0

## v2.1.0 - 2024-07-29

### What's Changed

* Add db-audit file as publish file. by @ruchit288 in https://github.com/vcian/laravel-db-auditor/pull/62

**Full Changelog**: https://github.com/vcian/laravel-db-auditor/compare/v2.0.1...v2.1.0

## v2.0.1 - 2024-07-19

### What's Changed

* Resolved issue and modified conditional statement.

## v2.0.0 - 2024-07-18

### What's Changed

* Enable SQLlite Audit feature.
* Now you can skip tables from the scan.
* You can now check direct table standard using "php artisan db: standard --table='tablename' ".
* Minor code refactor.
* Update readme.

**Full Changelog**: https://github.com/vcian/laravel-db-auditor/compare/v1.9.0...v2.0.0

## v1.9.0 - 2024-06-12

### What's Changed

* Now Support Laravel 11.
* Replace static value with constant and change the ordering of the field by @vc-urvin in https://github.com/vcian/laravel-db-auditor/pull/43
* #48 Resolve getDatatableSize function return type issue by @vc-dhavaljoshi in https://github.com/vcian/laravel-db-auditor/pull/49
* Turkish language support added. by @emresasi in https://github.com/vcian/laravel-db-auditor/pull/51
* #48 resolve issue getTableSize(): Return value must be of type string by @vc-dhavaljoshi in https://github.com/vcian/laravel-db-auditor/pull/52
* bump version to laravel 11 by @ibrahim-sakr in https://github.com/vcian/laravel-db-auditor/pull/45
* Bux fixing and improvement.

### New Contributors

* @emresasi made their first contribution in https://github.com/vcian/laravel-db-auditor/pull/51
* @ibrahim-sakr made their first contribution at https://github.com/vcian/laravel-db-auditor/pull/45

**Full Changelog**: https://github.com/vcian/laravel-db-auditor/compare/v1.8.1...v1.9.0

## v1.8.1 - 2024-01-19

### What's Changed

* Add Current User Name If Git commit not found or not commited the file by @vc-urvin in https://github.com/vcian/laravel-db-auditor/pull/42

**Full Changelog**: https://github.com/vcian/laravel-db-auditor/compare/v1.8.0...v1.8.1

## v1.8.0 - 2024-01-18

### What's Changed

* Add quotes in SQL query by @vc-urvin in https://github.com/vcian/laravel-db-auditor/pull/37
* Implement the database track command by @vc-urvin in https://github.com/vcian/laravel-db-auditor/pull/38
* Add an art folder with screenshots. by @ruchit288 in https://github.com/vcian/laravel-db-auditor/pull/39
* Create update-changelog.yml by @ruchit288 in https://github.com/vcian/laravel-db-auditor/pull/40
* feat: add database track related screenshot and update readme file. by @ruchit288 in https://github.com/vcian/laravel-db-auditor/pull/41

**Full Changelog**: https://github.com/vcian/laravel-db-auditor/compare/v1.7.0...v1.8.0

## v1.7.0 [3rd July 2023]

- Integrate Web Page For Standard Check.
- Integrate Web Page for Constraint Page with Actions - Add primary key, index, foreign and unique keys.
- Minor bug fixing.

## v1.6.0 [15th June 2023]

- Folder structure change - Services replaced with Traits.

## v1.5.2 [19th May 2023]

- UI/UX improvement of db:standard CLI UI.
- Resolved foreign key constraint issue.

## v1.5.1 [5th May 2023]

- Bug fixing and Add new Constraint.
- Change in rules services for suggestion messages.
- Add condition for empty constraint.
- Add examples in the readme file.

## v1.5.0 [29th April 2023]

- Resolved naming validation rules and datatype issue.

## v1.4.0 [27th April 2023]

- Unique constraint validation for duplicate values.
- Add suggestions for varchar datatype.
- Update messages.
- Update UI for standard and constraint results.

## v1.3.0 [25th April 2023]

- UI/UX improvement and minor bug fixings.

## v1.2.0 [21st April 2023]

- UI/UX improvement in CLI.
- Resolved bugs.
- Code cleanup.

## v1.1.1 [18st April 2023]

- Resolved class naming convention issue.
- Modified messages.

## v1.1.0 [17th April 2023]

- Update doctrine dependency.

## v1.0.0 [14th April 2023]

- Initial release.
