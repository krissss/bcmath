# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.0] - 2026-01-12

### Changed
- **Breaking**: 最低 PHP 版本要求升级至 8.2（原 7.3||^8.0）
- 添加完整的 PHP 类型声明和 PHPDoc 注解

### Fixed
- 修复 average() 方法在负数场景下的精度分配问题
- ceil 模式：从前往后分配，确保不超过剩余总数
- floor 和 round 模式：差额给最后一个元素

### Added
- 支持 PHP 8.5 测试

### Docs
- 添加 CLAUDE.md 项目指南
- 添加 CHANGELOG.md

## [2.5.4] - 2025-03-27

### Added
- 支持 PHP 8.4

### Fixed
- 修复 BSC 函数传 null 报错："bcxxx(): Passing null to parameter #2 ($num2) of type string is deprecated" (#13)
- 修复 tests 分支

## [2.5.3] - 2024-07-19

### Fixed
- 修复 number_format 精度问题 (#12)

## [2.5.2] - 2024-06-26

### Changed
- 对于非科学计数法的数字，不转 number_format (#10)

## [2.5.1] - 2024-06-25

### Added
- 支持使用科学计数法比较 (1e3)

## [2.5.0] - 2024-06-05

### Added
- 添加 GitHub Actions CI
- 补充测试用例提高测试覆盖率

### Changed
- 测试框架迁移至 Pest
- Parser 使用 BC 类

## [2.4.0] - 2024-06-05

### Fixed
- 使用 number_format 解决科学计数法问题

## [2.3] - 2021-11-18

### Fixed
- 修复精度为 0 时显示多个 0 的 bug

## [2.2] - 2021-11-18

### Fixed
- 修复末位为 0 的时候显示带 `.` 的 bug

## [2.1] - 2021-11-17

### Changed
- 重新处理进位和舍位的逻辑

## [2.0] - 2020-10-23

### Added
- 增加自动处理舍位和向上
- 添加 config round / ceil / floor

### Changed
- 移除 `rounded` 方法
- 默认使用 rounded 行为

## [1.1] - 2019-11-21

### Added
- 添加 BCSummary 类

## [1.0] - 2018-08-15

### Added
- 初始版本发布

[3.0.0]: https://github.com/krissss/bcmath/compare/v2.5.4...v3.0.0
[2.5.4]: https://github.com/krissss/bcmath/compare/v2.5.3...v2.5.4
[2.5.3]: https://github.com/krissss/bcmath/compare/v2.5.2...v2.5.3
[2.5.2]: https://github.com/krissss/bcmath/compare/v2.5.1...v2.5.2
[2.5.1]: https://github.com/krissss/bcmath/compare/v2.5.0...v2.5.1
[2.5.0]: https://github.com/krissss/bcmath/compare/v2.4.0...v2.5.0
[2.4.0]: https://github.com/krissss/bcmath/compare/v2.3...v2.4.0
[2.3]: https://github.com/krissss/bcmath/compare/v2.2...v2.3
[2.2]: https://github.com/krissss/bcmath/compare/v2.1...v2.2
[2.1]: https://github.com/krissss/bcmath/compare/v2.0...v2.1
[2.0]: https://github.com/krissss/bcmath/compare/v1.1...v2.0
[1.1]: https://github.com/krissss/bcmath/compare/v1.0...v1.1
[1.0]: https://github.com/krissss/bcmath/releases/tag/v1.0
