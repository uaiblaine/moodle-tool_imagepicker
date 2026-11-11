@tool @tool_imagepicker @javascript @_file_upload
Feature: Cropping an image into a field which limits the size of a cropped image
  In order to store an image at the size at which it is shown
  As a user of a form with an image picker
  I need a cropped image to be reduced to the size limit of its field

  Background:
    Given the following config values are set as admin:
      | preserveoriginals | 0 | tool_imagepicker |
    And I am on the "tool_imagepicker > Demo" page logged in as "admin"

  Scenario: Crop maxsize: A crop larger than the limit is reduced to fit within it, keeping its aspect ratio
    Given I upload "admin/tool/imagepicker/tests/fixtures/image.png" file to "Image (at most 200 x 100 pixels)" filemanager
    When I open the crop modal of "imagepickersized"
    Then I should see "The cropped image is reduced to fit within 200 x 100 pixels" in the ".tool-imagepicker-crop-modal" "css_element"
    # The whole 400 x 300 image: the height limit is the tighter one, so it comes out 133 x 100.
    When I set the crop selection to "0 / 0 / 1 / 1"
    And I save the crop
    And I press "Save changes"
    Then I should see "Changes saved"
    And the image of "imagepickersized" should have the dimensions "133 x 100"

  Scenario: Crop maxsize: A crop smaller than the limit keeps its own pixels and is never enlarged
    Given I upload "admin/tool/imagepicker/tests/fixtures/image.png" file to "Image (at most 200 x 100 pixels)" filemanager
    When I open the crop modal of "imagepickersized"
    # A quarter of the width and a fifth of the height: 100 x 60 pixels, inside both limits.
    And I set the crop selection to "0 / 0 / 0.25 / 0.2"
    And I save the crop
    And I press "Save changes"
    Then I should see "Changes saved"
    And the image of "imagepickersized" should have the dimensions "100 x 60"

  Scenario: Crop maxsize: A field without a size limit says nothing about one
    Given I upload "admin/tool/imagepicker/tests/fixtures/image.png" file to "Image" filemanager
    When I open the crop modal of "imagepicker"
    Then the cropper should be ready
    And I should not see "The cropped image is reduced" in the ".tool-imagepicker-crop-modal" "css_element"
