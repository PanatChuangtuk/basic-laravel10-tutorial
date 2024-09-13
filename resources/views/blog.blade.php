@extends('layouts.app')
@section('title', 'สถานะของคำร้องทั้งหมด')
@section('content')
    @if (count($blogs)>0)
    <h2 class="text text-center py-2">สถานะของคำร้องทั้งหมด</h2>
    <table class="table table-bordered text-center">
        <thead>
            <th><input type="checkbox">
                <th scope="col">ชื่อคำร้อง</th>
                <th scope="col">สถานะคำร้อง</th>
                <th scope="col">แก้ไขคำร้อง</th>
                <th scope="col">ลบคำร้อง</th>
            </th>
        </thead>
        <tbody>
            @foreach ($blogs as $item)
                <tr>
                    <td><input type="checkbox" name="id" class="checkboxes" value="{{$item->id}}" /></td>
                    <td>{{$item->title}}</td>
                    <td>
                        @if ($item->status==true)
                            <a href="{{route('change',$item->id)}}" class="btn btn-success">ยืม</a>
                        @else
                            <a href="{{route('change',$item->id)}}" class="btn btn btn-outline-success">คืน</a>
                        @endif
                    </td>
                    <td><a href="{{route('edit',$item->id)}}" class="btn btn-warning">แก้ไข</a></td>
                    <td>
                        <a 
                        href="{{route('delete',$item->id)}}" 
                        class="btn btn-danger"
                        onclick="return confirm('คุณต้องการลบบทความ {{$item->title}} หรือไม่ ?')"
                        >ลบ
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{$blogs->links()}}
    @else
        <h2 class="text text-center py-2">ไม่มีบทความในระบบ</h2>
    @endif
@endsection
