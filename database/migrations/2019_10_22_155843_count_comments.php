<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CountComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('comments')->default(0)->after('visits');
        });

        // 得到所有评论
        $all_comments = DB::table('comments')->select('id', 'post_id')->get();

        $count = [];

        // 统计每篇文章的评论数
        foreach ($all_comments as $comment)
        {
            if (array_key_exists($comment->post_id, $count))
            {
                $count[$comment->post_id]++;
            } else {
                $count[$comment->post_id] = 1;
            }
        }

        if (!empty($comment)) {
            // 将每篇文章的评论数 UPDATE 入数据库
            $sql = sprintf("UPDATE `%sposts` SET `comments` = CASE", config('database.connections.mysql.prefix'));
            $where = '(';
            foreach ($count as $k => $v)
            {
                $sql .= sprintf(" WHEN `id` = '%s' THEN '%s' ", $k, $v);
                $where .= sprintf("'%s',", $k);
            }
            $where = rtrim($where, ',');
            $where .= ")";
            $sql .= " ELSE `comments` END WHERE `id` IN ".$where;
            // echo $sql;
            DB::statement($sql);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('comments');
        });
    }
}
