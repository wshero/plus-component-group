@extends('group::layouts.app')
@section('content')
    <div class="panel-heading">
        <div class="form-inline">
            <div class="form-group">
                <label for="">搜索：</label>
                <input type="text" class="form-control" id="keyword" placeholder="关键词搜索" name="keyword">
            </div>
            <div class="form-group">
                <label for="">状态</label>
                <select id="audit" class="form-control">
                    <option value="">全部</option>
                    <option value="1">已审核</option>
                    <option value="0">未审核</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">圈子：</label>
                <select id="group_id" class="form-control">
                    <option value="">全部</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-default" id="search">搜索</button>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- 列表数据 -->
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>圈子</th>
                <th>阅读数</th>
                <th>点赞数</th>
                <th>收藏数</th>
                <th>评论数</th>
                <th>用户</th>
                <th>状态</th>
                <th>创建日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if($posts->count())
                    @foreach($posts as $post)
                        <tr>
                            <td>{{ $post->group->title }}</td>
                            <td>{{ $post->views }}</td>
                            <td><a href="{{ route('post:digg', $post->id) }}">{{ $post->diggs }}</a></td>
                            <td><a href="{{ route('post:collection', $post->id) }}">{{ $post->collections }}</a></td>
                            <td><a href="{{ route('post:comment', $post->id)}}">{{ $post->comments_count }}</a></td>
                            <td>{{ $post->user->name }}</td>
                            <td>
                                <a class="label {{ $post->is_audit ? 'label-success':'label-danger' }}" href="javascript:;">
                                    {{ $post->is_audit ? '已审核' : '未审核' }}</a>
                                <form action="{{ route('post:audit', $post->id) }}" method="post">
                                    {{ method_field('PATCH') }}
                                    {{ csrf_field() }}
                                </form>
                            </td>
                            <td>{{ $post->created_at }}</td>
                            <td>
                                <form action="{{ route('post:delete', $post->id) }}" method="post">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <button class="btn btn-danger btn-sm del-btn" type="button">删除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="10" style="text-align: center;">无相关记录</td></tr>
                @endif
            </tbody>
        </table>
        <!-- 分页 -->
        <div class="text-center">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        $(function(){

            @if (isset($query['keyword']))
                $('#keyword').val("{{ $query['keyword'] }}");
            @endif

            @if (isset($query['audit']))
                $('#audit').val("{{ $query['audit'] }}");
            @endif

            @if( isset($query['group_id']) )
                $('#group_id').val("{{ $query['group_id'] }}");
            @endif


            /**
             * 获取筛选参数
             * @returns {string}
             */
            function getQueryParams()
            {
                var keyword = $('#keyword').val();
                var group_id   = $('#group_id').val();
                var audit = $('#audit').val();
                var query = '';

                if ( keyword ) {
                    query += '?keyword=' + keyword;
                }
                if ( group_id ) {
                    if ( query.indexOf('?') !== -1 ) {
                        query += '&group_id=' + group_id;
                    } else {
                        query += '?group_id=' + group_id;
                    }
                }

                if ( audit ) {
                    if ( query.indexOf('?') !== -1 ) {
                        query += '&audit=' + audit;
                    } else {
                        query += '?audit=' + audit;
                    }
                }

                return query;
            }

            /**
             * 状态更新
             */
            $('.label').click(function () {
                $(this).parent().find('form').submit();
            });

            $('#search').click(function(){
                var query = getQueryParams();
                var url = window.location.origin+window.location.pathname;
                window.location = url+query;
            });

            deleteConfirm('确定要删除帖子吗？');
        })
    </script>
@endsection