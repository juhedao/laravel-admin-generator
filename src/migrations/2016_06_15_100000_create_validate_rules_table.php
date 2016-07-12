<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/12
 * Time: 14:46
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Contracts\Hashing\Hasher;

class CreateValidateRulesTable extends Migration
{
    public function up()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->create('validate_rules', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name',255)->comment('名称');
            $table->string('demo',255)->comment('演示');
            $table->string('describe',500)->comment('演示');
            $table->comment = 'PHP artisan 命令表';
        });
        DB::connection('juhedao_admin_generator_sqlite')->table('validate_rules')->insert(array(
            [
                'name'       => 'required',
                'demo'       => '',
                'describe'   => '字段值为必填。'
            ],
            [
                'name'       => 'email',
                'demo'       => '',
                'describe'   => '字段值需符合 email 格式。'
            ],
            [
                'name'       => 'min',
                'demo'       => 'value',
                'describe'   => '字段值需大于等于 value。对字串、数字和文件的判断依据 size 规则。'
            ],
            [
                'name'       => 'max',
                'demo'       => 'value',
                'describe'   => '字段值需小于等于 value。对字串、数字和文件的判断依据 size 规则。'
            ],
            [
                'name'       => 'unique',
                'demo'       => 'table,column,except,idColumn',
                'describe'   => '字段值在给定的数据库中需为唯一值。如果 column（字段） 选项没有指定，将会使用字段名称。'
            ],
            [
                'name'       => 'between',
                'demo'       => 'min,max',
                'describe'   => '字段值需介于指定的 min 和 max 值之间。字串、数值或是文件都是用同样的方式来进行验证。'
            ],
            [
                'name'       => 'numeric',
                'demo'       => '',
                'describe'   => '字段值需为数字。'
            ],
            [
                'name'       => 'confirmed',
                'demo'       => '',
                'describe'   => '字段值需与对应的字段值 foo_confirmation 相同。例如，如果验证的字段是 password ，那对应的字段 password_confirmation 就必须存在且与 password 字段相符。'
            ],
            [
                'name'       => 'accepted',
                'demo'       => '',
                'describe'   => '字段值为 yes, on, 或是 1 时，验证才会通过。这在确认"服务条款"是否同意时很有用。'
            ],
            [
                'name'       => 'alpha',
                'demo'       => '',
                'describe'   => '字段仅全数为字母字串时通过验证。'
            ],
            [
                'name'       => 'alpha_dash',
                'demo'       => '',
                'describe'   => '字段值仅允许字母、数字、破折号（-）以及底线（_）'
            ],
            [
                'name'       => 'alpha_num',
                'demo'       => '',
                'describe'   => '字段值仅允许字母、数字'
            ],
            [
                'name'       => 'array',
                'demo'       => '',
                'describe'   => '字段值仅允许为数组'
            ],
            [
                'name'       => 'before',
                'demo'       => 'date',
                'describe'   => '验证字段是否是在指定日期之前。这个日期将会使用 PHP strtotime 函数验证。'
            ],
            [
                'name'       => 'after',
                'demo'       => 'date',
                'describe'   => '字段值通过 PHP 函数 checkdnsrr 来验证是否为一个有效的网址。'
            ],
            [
                'name'       => 'boolean',
                'demo'       => '',
                'describe'   => '需要验证的字段必须可以转换为 boolean 类型的值。可接受的输入是true、false、1、0、"1" 和 "0"。'
            ],

            [
                'name'       => 'date_format',
                'demo'       => 'format',
                'describe'   => '字段值通过 PHP date_parse_from_format 函数验证符合 format 制定格式的日期是否为合法日期。'
            ],
            [
                'name'       => 'different',
                'demo'       => 'field',
                'describe'   => '字段值需与指定的字段 field 值不同。'
            ],
            [
                'name'       => 'digits',
                'demo'       => 'value',
                'describe'   => '字段值需为数字且长度需为 value。'
            ],
            [
                'name'       => 'digits_between',
                'demo'       => 'min,max',
                'describe'   => '字段值需为数字，且长度需介于 min 与 max 之间。'
            ],
            [
                'name'       => 'exists',
                'demo'       => 'table,column',
                'describe'   => '字段值需与存在于数据库 table 中的 column 字段值其一相同。'
            ],
            [
                'name'       => 'image',
                'demo'       => '',
                'describe'   => '字段值需与存在于数据库 table 中的 column 字段值其一相同。'
            ],
            [
                'name'       => 'in',
                'demo'       => 'str1,str2',
                'describe'   => '字段值需符合事先给予的清单的其中一个值'
            ],
            [
                'name'       => 'integer',
                'demo'       => '',
                'describe'   => '字段值需为一个整数值'
            ],
            [
                'name'       => 'ip',
                'demo'       => '',
                'describe'   => '字段值需符合 IP 位址格式。'
            ],

            [
                'name'       => 'mimes',
                'demo'       => 'foo,bar',
                'describe'   => '文件的 MIME 类需在给定清单中的列表中才能通过验证。'
            ],

            [
                'name'       => 'not_in',
                'demo'       => 'foo,bar',
                'describe'   => '字段值不得为给定清单中其一。'
            ],

            [
                'name'       => 'regex',
                'demo'       => 'pattern',
                'describe'   => '字段值需符合给定的正规表示式'
            ],
            [
                'name'       => 'required_if',
                'demo'       => 'field,value,...',
                'describe'   => '字段值在 field 字段值为 value 时为必填。'
            ],
            [
                'name'       => 'required_with',
                'demo'       => 'foo,bar,...',
                'describe'   => '字段值 仅在 任一指定字段有值情况下为必填。'
            ],
            [
                'name'       => 'required_with_all',
                'demo'       => 'foo,bar,...',
                'describe'   => '字段值 仅在 所有指定字段皆有值情况下为必填。'
            ],
            [
                'name'       => 'required_without',
                'demo'       => 'foo,bar,...',
                'describe'   => '字段值 仅在 任一指定字段没有值情况下为必填。'
            ],
            [
                'name'       => 'required_without_all',
                'demo'       => 'foo,bar,...',
                'describe'   => '字段值 仅在 所有指定字段皆没有值情况下为必填。'
            ],
            [
                'name'       => 'same',
                'demo'       => 'value',
                'describe'   => '字段值的大小需符合给定的 value 值。对于字串来说，value 为字串长度；'
            ],
            [
                'name'       => 'timezone',
                'demo'       => '',
                'describe'   => '字段值通过 PHP timezone_identifiers_list 函数来验证是否为有效的时区。'
            ],

            [
                'name'       => 'url',
                'demo'       => '',
                'describe'   => '字段值需符合 URL 的格式。'
            ],
            [
                'name'       => 'active_url',
                'demo'       => '',
                'describe'   => '字段值通过 PHP 函数 checkdnsrr 来验证是否为一个有效的网址。'
            ],
        ));
    }

    public function down()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->drop('validate_rules');
    }
}