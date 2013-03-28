## An Important Note About Support

Campaigner Multi-Select (in common with all of my ExpressionEngine add-ons) is no longer officially supported.

The remainder of this README provides basic usage information, and you are free to fork the repo if you'd like to make some changes or improvements (it's distributed under a liberal open source license).

Hopefully this will be everything you need to use this add-on in your projects, but if not please don't email me asking for support; I don't even have ExpressionEngine installed locally any more.

## Overview

Campaigner Multi-Select (hereafter Campaigner MS) is an ExpressionEngine
extension which extends the capabilities of the [Campaigner][campaigner_home]
add-on.

[campaigner_home]:http://experienceinternet.co.uk/software/campaigner/

Campaigner MS implements the ability to use a custom member field containing an
array of values as a trigger field.

## The problem
Imagine you have a custom member field with the short name `preferred_colors`.
The `preferred_colors` member field is populated via a standard registration
form, as follows.

    <!-- Form tags omitted for the sake of clarity. -->
    <label for="preferred_colors[]">Preferred colours</label>

    <label>
      <input type="checkbox" name="preferred_colors[]" value="R" /> Red
    </label>

    <label>
      <input type="checkbox" name="preferred_colors[]" value="G" /> Green
    </label>

    <label>
      <input type="checkbox" name="preferred_colors[]" value="B" /> Blue
    </label>

You only want to add a member to our mailing list if he likes the colour red,
so you set the trigger field to `preferred_colors`, and the trigger value to
`R`.

Now, if the member likes _only_ the colour red, the `preferred_colors` field
will contain `R` (which matches the stated trigger value), and the subscription
will work.

If the member likes red _and_ green, the `preferred_colors` field will contain
something like "R\nG" (values are delimited by line breaks). This won't match
the trigger value, so the subscription won't take place.

## The solution
Campaigner MS solves this problem by registering with the
`campaigner_should_subscribe_member` hook, and overriding Campaigner's default
"trigger match" check.

Non multi-select trigger fields are unaffected.
