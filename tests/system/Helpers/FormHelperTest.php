<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Helpers;

use CodeIgniter\HTTP\SiteURI;
use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
use Config\DocTypes;
use Config\Filters;
use Config\Services;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;

/**
 * @internal
 */
#[Group('SeparateProcess')]
final class FormHelperTest extends CIUnitTestCase
{
    #[WithoutErrorHandler]
    protected function setUp(): void
    {
        $this->resetServices();

        parent::setUp();

        helper('form');
    }

    private function setRequest(): void
    {
        $config            = new App();
        $config->indexPage = 'index.php';

        $uri = new SiteURI($config);
        Services::injectMock('uri', $uri);

        $request = service('request', $config);
        Services::injectMock('request', $request);
    }

    private function setCsrfFilter(): void
    {
        $filters                      = config(Filters::class);
        $filters->globals['before'][] = 'csrf';
        service('filters')->initialize();
    }

    public function testFormOpenBasic(): void
    {
        $this->setRequest();

        $expected = <<<'EOH'
            <form action="http://example.com/index.php/foo/bar" name="form" id="form" method="POST" accept-charset="utf-8">

            EOH;
        $attributes = [
            'name'   => 'form',
            'id'     => 'form',
            'method' => 'POST',
        ];
        $this->assertSame($expected, form_open('foo/bar', $attributes));
    }

    public function testFormOpenBasicWithCsrf(): void
    {
        $this->setRequest();
        $this->setCsrfFilter();

        $value    = csrf_hash();
        $name     = csrf_token();
        $expected = <<<EOH
            <form action="http://example.com/index.php/foo/bar" name="form" id="form" method="POST" accept-charset="utf-8">
            <input type="hidden" name="{$name}" value="{$value}">
            EOH;

        $attributes = [
            'name'   => 'form',
            'id'     => 'form',
            'method' => 'POST',
        ];
        $this->assertSame($expected, form_open('foo/bar', $attributes));
    }

    public function testFormOpenHasLocale(): void
    {
        $this->setRequest();

        $expected = <<<'EOH'
            <form action="http://example.com/index.php/en/foo/bar" name="form" id="form" method="POST" accept-charset="utf-8">

            EOH;

        $attributes = [
            'name'   => 'form',
            'id'     => 'form',
            'method' => 'POST',
        ];
        $this->assertSame($expected, form_open('{locale}/foo/bar', $attributes));
    }

    public function testFormOpenWithoutAction(): void
    {
        $this->setRequest();

        $expected = <<<'EOH'
            <form action="http://example.com/index.php" name="form" id="form" method="POST" accept-charset="utf-8">

            EOH;
        $attributes = [
            'name'   => 'form',
            'id'     => 'form',
            'method' => 'POST',
        ];
        $this->assertSame($expected, form_open('', $attributes));
    }

    public function testFormOpenWithoutActionWithCsrf(): void
    {
        $this->setRequest();
        $this->setCsrfFilter();

        $value    = csrf_hash();
        $name     = csrf_token();
        $expected = <<<EOH
            <form action="http://example.com/index.php" name="form" id="form" method="POST" accept-charset="utf-8">
            <input type="hidden" name="{$name}" value="{$value}">
            EOH;
        $attributes = [
            'name'   => 'form',
            'id'     => 'form',
            'method' => 'POST',
        ];
        $this->assertSame($expected, form_open('', $attributes));
    }

    public function testFormOpenWithoutMethod(): void
    {
        $this->setRequest();

        $expected = <<<'EOH'
            <form action="http://example.com/index.php/foo/bar" name="form" id="form" method="post" accept-charset="utf-8">

            EOH;

        $attributes = [
            'name' => 'form',
            'id'   => 'form',
        ];
        $this->assertSame($expected, form_open('foo/bar', $attributes));
    }

    public function testFormOpenWithoutMethodWithCsrf(): void
    {
        $this->setRequest();
        $this->setCsrfFilter();

        $value    = csrf_hash();
        $name     = csrf_token();
        $expected = <<<EOH
            <form action="http://example.com/index.php/foo/bar" name="form" id="form" method="post" accept-charset="utf-8">
            <input type="hidden" name="{$name}" value="{$value}">
            EOH;
        $attributes = [
            'name' => 'form',
            'id'   => 'form',
        ];
        $this->assertSame($expected, form_open('foo/bar', $attributes));
    }

    public function testFormOpenWithHidden(): void
    {
        $this->setRequest();

        $expected = <<<'EOH'
            <form action="http://example.com/index.php/foo/bar" name="form" id="form" method="POST" accept-charset="utf-8">

            <input type="hidden" name="foo" value="bar">

            EOH;
        $attributes = [
            'name'   => 'form',
            'id'     => 'form',
            'method' => 'POST',
        ];
        $hidden = [
            'foo' => 'bar',
        ];
        $this->assertSame($expected, form_open('foo/bar', $attributes, $hidden));
    }

    public function testFormOpenWithHiddenWithCsrf(): void
    {
        $this->setRequest();
        $this->setCsrfFilter();

        $value    = csrf_hash();
        $name     = csrf_token();
        $expected = <<<EOH
            <form action="http://example.com/index.php/foo/bar" name="form" id="form" method="POST" accept-charset="utf-8">
            <input type="hidden" name="{$name}" value="{$value}">
            <input type="hidden" name="foo" value="bar">

            EOH;
        $attributes = [
            'name'   => 'form',
            'id'     => 'form',
            'method' => 'POST',
        ];
        $hidden = [
            'foo' => 'bar',
        ];
        $this->assertSame($expected, form_open('foo/bar', $attributes, $hidden));
    }

    public function testFormOpenMultipart(): void
    {
        $this->setRequest();

        $expected = <<<'EOH'
            <form action="http://example.com/index.php/foo/bar" name="form" id="form" method="POST" enctype="multipart/form-data" accept-charset="utf-8">

            EOH;
        $attributes = [
            'name'   => 'form',
            'id'     => 'form',
            'method' => 'POST',
        ];
        $this->assertSame($expected, form_open_multipart('foo/bar', $attributes));

        // make sure it works with attributes as a string too
        $attributesString = 'name="form" id="form" method="POST"';
        $this->assertSame($expected, form_open_multipart('foo/bar', $attributesString));
    }

    public function testFormOpenMultipartWithCsrf(): void
    {
        $this->setRequest();
        $this->setCsrfFilter();

        $value    = csrf_hash();
        $name     = csrf_token();
        $expected = <<<EOH
            <form action="http://example.com/index.php/foo/bar" name="form" id="form" method="POST" enctype="multipart/form-data" accept-charset="utf-8">
            <input type="hidden" name="{$name}" value="{$value}">
            EOH;
        $attributes = [
            'name'   => 'form',
            'id'     => 'form',
            'method' => 'POST',
        ];
        $this->assertSame($expected, form_open_multipart('foo/bar', $attributes));

        // make sure it works with attributes as a string too
        $attributesString = 'name="form" id="form" method="POST"';
        $this->assertSame($expected, form_open_multipart('foo/bar', $attributesString));
    }

    public function testFormHidden(): void
    {
        $expected = <<<EOH

            <input type="hidden" name="username" value="johndoe">\n
            EOH;
        $this->assertSame($expected, form_hidden('username', 'johndoe'));
    }

    public function testFormHiddenArrayInput(): void
    {
        $data = [
            'foo' => 'bar',
        ];
        $expected = <<<'EOH'

            <input type="hidden" name="foo" value="bar">

            EOH;
        $this->assertSame($expected, form_hidden($data, null));
    }

    public function testFormHiddenArrayValues(): void
    {
        $data = [
            'foo' => 'bar',
        ];
        $expected = <<<'EOH'

            <input type="hidden" name="name[foo]" value="bar">

            EOH;
        $this->assertSame($expected, form_hidden('name', $data));
    }

    public function testFormInput(): void
    {
        $expected = <<<EOH
            <input type="text" name="username" value="johndoe" id="username" maxlength="100" size="50" style="width:50%">\n
            EOH;
        $data = [
            'name'      => 'username',
            'id'        => 'username',
            'value'     => 'johndoe',
            'maxlength' => '100',
            'size'      => '50',
            'style'     => 'width:50%',
        ];
        $this->assertSame($expected, form_input($data));
    }

    public function testFormInputXhtml(): void
    {
        $this->disableHtml5();

        $expected = <<<EOH
            <input type="text" name="username" value="johndoe" id="username" maxlength="100" size="50" style="width:50%" />\n
            EOH;
        $data = [
            'name'      => 'username',
            'id'        => 'username',
            'value'     => 'johndoe',
            'maxlength' => '100',
            'size'      => '50',
            'style'     => 'width:50%',
        ];
        $this->assertSame($expected, form_input($data));

        $this->enableHtml5();
    }

    private function disableHtml5(): void
    {
        $doctypes        = new DocTypes();
        $doctypes->html5 = false;
        _solidus($doctypes);
    }

    private function enableHtml5(): void
    {
        $doctypes = new DocTypes();
        _solidus($doctypes);
    }

    public function testFormInputWithExtra(): void
    {
        $expected = <<<EOH
            <input type="email" name="identity" value="" id="identity" class="form-control form-control-lg">\n
            EOH;
        $data = [
            'id'   => 'identity',
            'name' => 'identity',
            'type' => 'email',
        ];
        $extra = [
            'class' => 'form-control form-control-lg',
        ];
        $this->assertSame($expected, form_input($data, '', $extra));
    }

    public function testFormPassword(): void
    {
        $expected = <<<EOH
            <input type="password" name="password" value="">\n
            EOH;
        $this->assertSame($expected, form_password('password'));
    }

    public function testFormUpload(): void
    {
        $expected = <<<EOH
            <input type="file" name="attachment">\n
            EOH;
        $this->assertSame($expected, form_upload('attachment'));
    }

    public function testFormUploadXhtml(): void
    {
        $this->disableHtml5();

        $expected = <<<EOH
            <input type="file" name="attachment" />\n
            EOH;
        $this->assertSame($expected, form_upload('attachment'));

        $this->enableHtml5();
    }

    public function testFormTextarea(): void
    {
        $expected = <<<EOH
            <textarea name="notes" cols="40" rows="10">Notes</textarea>\n
            EOH;
        $this->assertSame($expected, form_textarea('notes', 'Notes'));
    }

    public function testFormTextareaWithValueAttribute(): void
    {
        $data = [
            'name'  => 'foo',
            'value' => 'bar',
        ];
        $expected = <<<'EOH'
            <textarea name="foo" cols="40" rows="10">bar</textarea>

            EOH;
        $this->assertSame($expected, form_textarea($data));
    }

    public function testFormTextareaExtraRowsColsArray(): void
    {
        $extra = [
            'cols' => '30',
            'rows' => '5',
        ];
        $expected = <<<EOH
            <textarea name="notes" cols="30" rows="5">Notes</textarea>\n
            EOH;
        $this->assertSame($expected, form_textarea('notes', 'Notes', $extra));
    }

    public function testFormTextareaExtraRowsColsString(): void
    {
        $extra    = 'cols="30" rows="5"';
        $expected = <<<EOH
            <textarea name="notes" cols="30" rows="5">Notes</textarea>\n
            EOH;
        $this->assertSame($expected, form_textarea('notes', 'Notes', $extra));
    }

    public function testFormDropdown(): void
    {
        $expected = <<<EOH
            <select name="shirts">
            <option value="small">Small Shirt</option>
            <option value="med">Medium Shirt</option>
            <option value="large" selected="selected">Large Shirt</option>
            <option value="xlarge">Extra Large Shirt</option>
            </select>\n
            EOH;
        $options = [
            'small'  => 'Small Shirt',
            'med'    => 'Medium Shirt',
            'large'  => 'Large Shirt',
            'xlarge' => 'Extra Large Shirt',
        ];
        $this->assertSame($expected, form_dropdown('shirts', $options, 'large'));
        $expected = <<<EOH
            <select name="shirts" multiple="multiple">
            <option value="small" selected="selected">Small Shirt</option>
            <option value="med">Medium Shirt</option>
            <option value="large" selected="selected">Large Shirt</option>
            <option value="xlarge">Extra Large Shirt</option>
            </select>\n
            EOH;
        $shirtsOnSale = [
            'small',
            'large',
        ];
        $this->assertSame($expected, form_dropdown('shirts', $options, $shirtsOnSale));
        $options = [
            'Swedish Cars' => [
                'volvo' => 'Volvo',
                'saab'  => 'Saab',
            ],
            'German Cars' => [
                'mercedes' => 'Mercedes',
                'audi'     => 'Audi',
            ],
        ];
        $expected = <<<EOH
            <select name="cars" multiple="multiple">
            <optgroup label="Swedish Cars">
            <option value="volvo" selected="selected">Volvo</option>
            <option value="saab">Saab</option>
            </optgroup>
            <optgroup label="German Cars">
            <option value="mercedes">Mercedes</option>
            <option value="audi" selected="selected">Audi</option>
            </optgroup>
            </select>\n
            EOH;
        $this->assertSame($expected, form_dropdown('cars', $options, ['volvo', 'audi']));
    }

    public function testFormDropdownUnselected(): void
    {
        $options = [
            'Swedish Cars' => [
                'volvo' => 'Volvo',
                'saab'  => 'Saab',
            ],
            'German Cars' => [
                'mercedes' => 'Mercedes',
                'audi'     => 'Audi',
            ],
        ];
        $expected = <<<EOH
            <select name="cars">
            <optgroup label="Swedish Cars">
            <option value="volvo">Volvo</option>
            <option value="saab">Saab</option>
            </optgroup>
            <optgroup label="German Cars">
            <option value="mercedes">Mercedes</option>
            <option value="audi">Audi</option>
            </optgroup>
            </select>\n
            EOH;
        $this->assertSame($expected, form_dropdown('cars', $options));
    }

    public function testFormDropdownKeyCasting(): void
    {
        $options = [
            'Swedish Cars' => [
                '1' => 'Volvo',
                '2' => 'Saab',
            ],
            2 => [
                3   => 'Mercedes',
                '4' => 'Audi',
            ],
        ];
        $selected = [2];
        $expected = <<<EOH
            <select name="cars">
            <optgroup label="Swedish Cars">
            <option value="1">Volvo</option>
            <option value="2" selected="selected">Saab</option>
            </optgroup>
            <optgroup label="2">
            <option value="3">Mercedes</option>
            <option value="4">Audi</option>
            </optgroup>
            </select>\n
            EOH;
        $this->assertSame($expected, form_dropdown('cars', $options, $selected));
    }

    public function testFormDropdownInferred(): void
    {
        $options = [
            'Swedish Cars' => [
                'volvo' => 'Volvo',
                'saab'  => 'Saab',
            ],
            'German Cars' => [
                'mercedes' => 'Mercedes',
                'audi'     => 'Audi',
            ],
        ];
        $expected = <<<EOH
            <select name="cars">
            <optgroup label="Swedish Cars">
            <option value="volvo">Volvo</option>
            <option value="saab">Saab</option>
            </optgroup>
            <optgroup label="German Cars">
            <option value="mercedes">Mercedes</option>
            <option value="audi" selected="selected">Audi</option>
            </optgroup>
            </select>\n
            EOH;
        $_POST['cars'] = 'audi';
        $this->assertSame($expected, form_dropdown('cars', $options));
        unset($_POST['cars']);
    }

    public function testFormDropdownWithSelectedAttribute(): void
    {
        $expected = <<<'EOH'
            <select name="foo">
            <option value="bar" selected="selected">Bar</option>
            </select>

            EOH;
        $data = [
            'name'     => 'foo',
            'selected' => 'bar',
        ];
        $options = [
            'bar' => 'Bar',
        ];
        $this->assertSame($expected, form_dropdown($data, $options));
    }

    public function testFormDropdownWithOptionsAttribute(): void
    {
        $expected = <<<'EOH'
            <select name="foo">
            <option value="bar">Bar</option>
            </select>

            EOH;
        $data = [
            'name'    => 'foo',
            'options' => [
                'bar' => 'Bar',
            ],
        ];
        $this->assertSame($expected, form_dropdown($data));
    }

    public function testFormDropdownWithEmptyArrayOptionValue(): void
    {
        $expected = <<<'EOH'
            <select name="foo">
            </select>

            EOH;
        $options = [
            'bar' => [],
        ];
        $this->assertSame($expected, form_dropdown('foo', $options));
    }

    public function testFormMultiselect(): void
    {
        $expected = <<<EOH
            <select name="shirts[]"  multiple="multiple">
            <option value="small">Small Shirt</option>
            <option value="med" selected="selected">Medium Shirt</option>
            <option value="large" selected="selected">Large Shirt</option>
            <option value="xlarge">Extra Large Shirt</option>
            </select>\n
            EOH;
        $options = [
            'small'  => 'Small Shirt',
            'med'    => 'Medium Shirt',
            'large'  => 'Large Shirt',
            'xlarge' => 'Extra Large Shirt',
        ];
        $this->assertSame($expected, form_multiselect('shirts[]', $options, ['med', 'large']));
    }

    public function testFormMultiselectArrayData(): void
    {
        $expected = <<<EOH
            <select name="shirts[]"  multiple="multiple">
            <option value="small">Small Shirt</option>
            <option value="med" selected="selected">Medium Shirt</option>
            <option value="large" selected="selected">Large Shirt</option>
            <option value="xlarge">Extra Large Shirt</option>
            </select>\n
            EOH;
        $options = [
            'small'  => 'Small Shirt',
            'med'    => 'Medium Shirt',
            'large'  => 'Large Shirt',
            'xlarge' => 'Extra Large Shirt',
        ];

        $data = [
            'name'     => 'shirts[]',
            'options'  => $options,
            'selected' => [
                'med',
                'large',
            ],
        ];

        $this->assertSame($expected, form_multiselect($data));
    }

    public function testFormFieldset(): void
    {
        $expected = <<<EOH
            <fieldset>
            <legend>Address Information</legend>\n
            EOH;
        $this->assertSame($expected, form_fieldset('Address Information'));
    }

    public function testFormFieldsetWithNoLegent(): void
    {
        $expected = <<<'EOH'
            <fieldset>

            EOH;
        $this->assertSame($expected, form_fieldset());
    }

    public function testFormFieldsetWithAttributes(): void
    {
        $attributes = [
            'name' => 'bar',
        ];
        $expected = <<<'EOH'
            <fieldset name="bar">
            <legend>Foo</legend>

            EOH;
        $this->assertSame($expected, form_fieldset('Foo', $attributes));
    }

    public function testFormFieldsetClose(): void
    {
        $expected = <<<'EOH'
            </fieldset></div></div>
            EOH;
        $this->assertSame($expected, form_fieldset_close('</div></div>'));
    }

    public function testFormCheckbox(): void
    {
        $expected = <<<EOH
            <input type="checkbox" name="newsletter" value="accept" checked="checked">\n
            EOH;
        $this->assertSame($expected, form_checkbox('newsletter', 'accept', true));
    }

    public function testFormCheckboxXhtml(): void
    {
        $this->disableHtml5();

        $expected = <<<EOH
            <input type="checkbox" name="newsletter" value="accept" checked="checked" />\n
            EOH;
        $this->assertSame($expected, form_checkbox('newsletter', 'accept', true));

        $this->enableHtml5();
    }

    public function testFormCheckboxArrayData(): void
    {
        $data = [
            'name'    => 'foo',
            'value'   => 'bar',
            'checked' => true,
        ];
        $expected = <<<'EOH'
            <input type="checkbox" name="foo" value="bar" checked="checked">

            EOH;
        $this->assertSame($expected, form_checkbox($data));
    }

    public function testFormCheckboxArrayDataWithCheckedFalse(): void
    {
        $data = [
            'name'    => 'foo',
            'value'   => 'bar',
            'checked' => false,
        ];
        $expected = <<<'EOH'
            <input type="checkbox" name="foo" value="bar">

            EOH;
        $this->assertSame($expected, form_checkbox($data));
    }

    public function testFormRadio(): void
    {
        $expected = <<<EOH
            <input type="radio" name="newsletter" value="accept" checked="checked">\n
            EOH;
        $this->assertSame($expected, form_radio('newsletter', 'accept', true));
    }

    public function testFormSubmit(): void
    {
        $expected = <<<EOH
            <input type="submit" name="mysubmit" value="Submit Post!">\n
            EOH;
        $this->assertSame($expected, form_submit('mysubmit', 'Submit Post!'));
    }

    public function testFormLabel(): void
    {
        $expected = <<<'EOH'
            <label for="username">What is your Name</label>
            EOH;
        $this->assertSame($expected, form_label('What is your Name', 'username'));
    }

    public function testFormLabelWithAttributes(): void
    {
        $attributes = [
            'id' => 'label1',
        ];
        $expected = <<<'EOH'
            <label for="foo" id="label1">bar</label>
            EOH;
        $this->assertSame($expected, form_label('bar', 'foo', $attributes));
    }

    public function testFormReset(): void
    {
        $expected = <<<EOH
            <input type="reset" name="myreset" value="Reset">\n
            EOH;
        $this->assertSame($expected, form_reset('myreset', 'Reset'));
    }

    public function testFormButton(): void
    {
        $expected = <<<EOH
            <button name="name" type="button">content</button>\n
            EOH;
        $this->assertSame($expected, form_button('name', 'content'));
    }

    public function testFormButtonWithDataArray(): void
    {
        $data = [
            'name'    => 'foo',
            'content' => 'bar',
        ];
        $expected = <<<'EOH'
            <button name="foo" type="button">bar</button>

            EOH;
        $this->assertSame($expected, form_button($data));
    }

    public function testFormClose(): void
    {
        $expected = <<<'EOH'
            </form></div></div>
            EOH;
        $this->assertSame($expected, form_close('</div></div>'));
    }

    public function testFormDatalist(): void
    {
        $options = [
            'foo1',
            'bar1',
        ];
        $expected = <<<'EOH'
            <input type="text" name="foo" value="bar" list="foo_list">

            <datalist id='foo_list'><option value='foo1'>
            <option value='bar1'>
            </datalist>

            EOH;
        $this->assertSame($expected, form_datalist('foo', 'bar', $options));
    }

    public function testSetValue(): void
    {
        $_SESSION['_ci_old_input']['post']['foo'] = '<bar';
        $this->assertSame('&lt;bar', set_value('foo'));

        unset($_SESSION['_ci_old_input']['post']['foo']);
        $this->assertSame('baz', set_value('foo', 'baz'));
    }

    public function testSetSelect(): void
    {
        $_SESSION['_ci_old_input']['post']['foo'] = 'bar';
        $this->assertSame(' selected="selected"', set_select('foo', 'bar'));

        $_SESSION['_ci_old_input']['post']['foo'] = ['foo' => 'bar'];
        $this->assertSame(' selected="selected"', set_select('foo', 'bar'));
        $this->assertSame('', set_select('foo', 'baz'));

        unset($_SESSION['_ci_old_input']['post']['foo']);
        $this->assertSame(' selected="selected"', set_select('foo', 'baz', true));
    }

    public function testSetCheckbox(): void
    {
        $_SESSION = [
            '_ci_old_input' => [
                'post' => [
                    'foo' => 'bar',
                ],
            ],
        ];

        $this->assertSame(' checked="checked"', set_checkbox('foo', 'bar'));

        $_SESSION = [
            '_ci_old_input' => [
                'post' => [
                    'foo' => ['foo' => 'bar'],
                ],
            ],
        ];
        $this->assertSame(' checked="checked"', set_checkbox('foo', 'bar'));
        $this->assertSame('', set_checkbox('foo', 'baz'));

        $_SESSION = [];
        $this->assertSame('', set_checkbox('foo', 'bar'));

        $_SESSION = [];
        $this->assertSame(' checked="checked"', set_checkbox('foo', 'bar', true));
    }

    public function testSetCheckboxWithValueZero(): void
    {
        $_SESSION = [
            '_ci_old_input' => [
                'post' => [
                    'foo' => '0',
                ],
            ],
        ];

        $this->assertSame(' checked="checked"', set_checkbox('foo', '0'));

        $_SESSION = [
            '_ci_old_input' => [
                'post' => [
                    'foo' => ['foo' => '0'],
                ],
            ],
        ];
        $this->assertSame(' checked="checked"', set_checkbox('foo', '0'));
        $this->assertSame('', set_checkbox('foo', 'baz'));

        $_SESSION = [];
        $this->assertSame('', set_checkbox('foo', 'bar'));

        $_SESSION = [];
        $this->assertSame(' checked="checked"', set_checkbox('foo', '0', true));
    }

    /**
     * @see https://github.com/codeigniter4/CodeIgniter4/issues/7814
     */
    public function testSetCheckboxWithUnchecked(): void
    {
        $_SESSION = [
            '_ci_old_input' => [
                'post' => [
                ],
            ],
        ];

        $this->assertSame(
            '',
            set_checkbox('fruit', 'apple', true)
        );

        $this->assertSame(
            '',
            set_checkbox('fruit', 'apple')
        );
    }

    #[PreserveGlobalState(false)]
    #[RunInSeparateProcess]
    public function testSetRadioFromSessionOldInput(): void
    {
        $_SESSION = [
            '_ci_old_input' => [
                'post' => [
                    'foo' => '<bar>',
                ],
            ],
        ];

        $this->assertSame(' checked="checked"', set_radio('foo', '<bar>'));
        $this->assertSame('', set_radio('foo', 'baz'));

        unset($_SESSION['_ci_old_input']);
    }

    #[PreserveGlobalState(false)]
    #[RunInSeparateProcess]
    public function testSetRadioFromPost(): void
    {
        $_POST['bar'] = 'baz';

        $this->assertSame(' checked="checked"', set_radio('bar', 'baz'));
        $this->assertSame('', set_radio('bar', 'boop'));
        $this->assertSame('', set_radio('bar', 'boop', true));
    }

    #[PreserveGlobalState(false)]
    #[RunInSeparateProcess]
    public function testSetRadioFromPostWithValueZero(): void
    {
        $_POST['bar'] = '0';

        $this->assertSame(' checked="checked"', set_radio('bar', '0'));
        $this->assertSame('', set_radio('bar', 'boop'));

        $_POST = [];

        $this->assertSame(' checked="checked"', set_radio('bar', '0', true));
    }

    public function testSetRadioFromSessionOldInputPostArray(): void
    {
        $_SESSION = [
            '_ci_old_input' => [
                'post' => [
                    'bar' => [
                        'boop',
                        'fuzzy',
                    ],
                ],
            ],
        ];

        $this->assertSame(' checked="checked"', set_radio('bar', 'boop'));
        $this->assertSame('', set_radio('bar', 'baz'));
    }

    public function testSetRadioFromSessionOldInputPostArrayWithValueZero(): void
    {
        $_SESSION = [
            '_ci_old_input' => [
                'post' => [
                    'bar' => [
                        '0',
                        'fuzzy',
                    ],
                ],
            ],
        ];

        $this->assertSame(' checked="checked"', set_radio('bar', '0'));
        $this->assertSame('', set_radio('bar', 'baz'));
    }

    public function testSetRadioDefault(): void
    {
        $_SESSION = [];
        $_POST    = [];

        $this->assertSame(' checked="checked"', set_radio('code', 'alpha', true));
        $this->assertSame('', set_radio('code', 'beta', false));
    }

    public function testValidationErrorsFromSession(): void
    {
        $_SESSION = ['_ci_validation_errors' => ['foo' => 'bar']];

        $this->assertSame(['foo' => 'bar'], validation_errors());

        $_SESSION = [];
    }

    public function testValidationErrorsFromValidation(): void
    {
        $validation = service('validation');
        $validation->setRule('id', 'ID', 'required')->run([]);

        $this->assertSame(['id' => 'The ID field is required.'], validation_errors());
    }

    public function testValidationListErrors(): void
    {
        $validation = service('validation');
        $validation->setRule('id', 'ID', 'required')->run([]);

        $html = validation_list_errors();

        $this->assertStringContainsString('<li>The ID field is required.</li>', $html);
    }

    public function testValidationShowError(): void
    {
        $validation = service('validation');
        $validation->setRule('id', 'ID', 'required')->run([]);

        $html = validation_show_error('id');

        $this->assertSame('<!-- DEBUG-VIEW START 1 SYSTEMPATH/Validation/Views/single.php -->
<span class="help-block">The ID field is required.</span>

<!-- DEBUG-VIEW ENDED 1 SYSTEMPATH/Validation/Views/single.php -->' . "\n", $html);
    }

    public function testValidationShowErrorForWildcards(): void
    {
        $validation = service('validation');
        $validation->setRule('user.*.name', 'Name', 'required')
            ->run([
                'user' => [
                    'friends' => [
                        ['name' => 'Name1'],
                        ['name' => ''],
                        ['name' => 'Name2'],
                    ],
                ],
            ]);

        $html = validation_show_error('user.*.name');

        $this->assertSame('<!-- DEBUG-VIEW START 1 SYSTEMPATH/Validation/Views/single.php -->
<span class="help-block">The Name field is required.</span>

<!-- DEBUG-VIEW ENDED 1 SYSTEMPATH/Validation/Views/single.php -->' . "\n", $html);
    }

    public function testFormParseFormAttributesTrue(): void
    {
        $expected = 'readonly ';
        $this->assertSame($expected, parse_form_attributes(['readonly' => true], []));
    }

    public function testFormParseFormAttributesFalse(): void
    {
        $expected = 'disabled ';
        $this->assertSame($expected, parse_form_attributes(['disabled' => false], []));
    }

    public function testFormParseFormAttributesNull(): void
    {
        $expected = 'bar=""';
        $this->assertSame($expected, parse_form_attributes(['bar' => null], []));
    }

    public function testFormParseFormAttributesStringEmpty(): void
    {
        $expected = 'bar=""';
        $this->assertSame($expected, parse_form_attributes(['bar' => ''], []));
    }

    public function testFormParseFormAttributesStringFoo(): void
    {
        $expected = 'bar="foo"';
        $this->assertSame($expected, parse_form_attributes(['bar' => 'foo'], []));
    }

    public function testFormParseFormAttributesInt0(): void
    {
        $expected = 'ok="0"';
        $this->assertSame($expected, parse_form_attributes(['ok' => 0], []));
    }

    public function testFormParseFormAttributesInt1(): void
    {
        $expected = 'ok="1"';
        $this->assertSame($expected, parse_form_attributes(['ok' => 1], []));
    }
}
