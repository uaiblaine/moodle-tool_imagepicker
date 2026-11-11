@tool @tool_imagepicker @javascript @_file_upload
Feature: Choosing the aspect ratio of a crop
  In order to crop an image to the shape of the place it is shown in
  As a user of a form with an image picker
  I need to choose among the aspect ratios the field offers, starting on the one it recommends

  Background:
    Given the following config values are set as admin:
      | preserveoriginals | 0 | tool_imagepicker |
    And I am on the "tool_imagepicker > Demo" page logged in as "admin"

  Scenario: Crop ratios: The dialog starts on the recommended ratio and marks it
    Given I upload "admin/tool/imagepicker/tests/fixtures/image.png" file to "Image (choice of aspect ratios)" filemanager
    When I open the crop modal of "imagepickerratios"
    And the cropper should be ready
    Then I should see "Aspect ratio" in the ".tool-imagepicker-ratios" "css_element"
    And I should see "Recommended" in the ".tool-imagepicker-ratios [aria-pressed='true']" "css_element"
    And I should see "16:9" in the ".tool-imagepicker-ratios [aria-pressed='true']" "css_element"
    And the crop selection should have the aspect ratio "16:9"
    And I should not see "[["

  Scenario: Crop ratios: Choosing another ratio reshapes the selection and the stored crop
    Given I upload "admin/tool/imagepicker/tests/fixtures/image.png" file to "Image (choice of aspect ratios)" filemanager
    When I open the crop modal of "imagepickerratios"
    And the cropper should be ready
    And I click on "1:1" "button" in the ".tool-imagepicker-ratios" "css_element"
    Then I should see "1:1" in the ".tool-imagepicker-ratios [aria-pressed='true']" "css_element"
    And the crop selection should have the aspect ratio "1:1"
    When I save the crop
    And I press "Save changes"
    Then I should see "Changes saved"
    # The largest square of the 400 x 300 image.
    And the image of "imagepickerratios" should have the dimensions "300 x 300"

  Scenario: Crop ratios: Free cropping lets the selection take any shape
    Given I upload "admin/tool/imagepicker/tests/fixtures/image.png" file to "Image (choice of aspect ratios)" filemanager
    When I open the crop modal of "imagepickerratios"
    And the cropper should be ready
    And I click on "Free" "button" in the ".tool-imagepicker-ratios" "css_element"
    And I set the crop selection to "0 / 0 / 0.5 / 1"
    Then the crop selection should cover approximately "0 / 0 / 0.5 / 1"
    When I save the crop
    And I press "Save changes"
    Then I should see "Changes saved"
    And the image of "imagepickerratios" should have the dimensions "200 x 300"

  Scenario: Crop ratios: A field without choices shows none
    Given I upload "admin/tool/imagepicker/tests/fixtures/image.png" file to "Image (16:9)" filemanager
    When I open the crop modal of "imagepickerratio"
    Then the cropper should be ready
    And ".tool-imagepicker-ratios" "css_element" should not exist
