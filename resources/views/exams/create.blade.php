@extends('layouts.app')
@section('title','امتحان جديد')
@section('content')

<style>
.diploma-picker {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
}

.diploma-search-box {
    padding: 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.diploma-list {
    max-height: 300px;
    overflow-y: auto;
    border-bottom: 1px solid #dee2e6;
}

.diploma-list-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: all 0.2s;
}

.diploma-list-item:hover {
    background: #e7f1ff;
    transform: translateX(-2px);
}

.diploma-list-empty {
    padding: 40px;
    text-align: center;
    color: #6c757d;
}

.diploma-list-empty i {
    font-size: 48px;
    margin-bottom: 10px;
    display: block;
}

.selected-diploma-container {
    min-height: 100px;
    padding: 15px;
    background: #fff;
}

.selected-diploma-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 15px;
    transition: all 0.2s;
}

.selected-diploma-card:hover {
    background: #dcfce7;
    border-color: #86efac;
}

.sd-info {
    flex: 1;
}

.sd-name {
    font-size: 16px;
    color: #166534;
}

.sd-code {
    font-size: 13px;
}

.sd-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.sd-badge.online {
    background: #dbeafe;
    color: #1e40af;
}

.sd-badge.onsite {
    background: #dcfce7;
    color: #166534;
}

.sd-remove {
    cursor: pointer;
    color: #3b82f6;
    font-size: 20px;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.2s;
    background: white;
}

.sd-remove:hover {
    background: #eff6ff;
    transform: scale(1.1);
}

.no-diploma-msg {
    text-align: center;
    color: #6c757d;
    padding: 20px;
}

.d-name {
    font-weight: 500;
    color: #1e293b;
}

.d-meta {
    font-size: 12px;
    margin-top: 4px;
}

/* تحسين عرض الأخطاء */
.alert-danger {
    background-color: #fee2e2;
    border: 1px solid #fecaca;
    color: #991b1b;
    border-radius: 8px;
}

.alert-danger ul {
    list-style: none;
    padding-right: 0;
}

.alert-danger li {
    padding: 5px 0;
}

.alert-danger li::before {
    content: "⚠ ";
    font-weight: bold;
}
</style>





<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h5 class="fw-bold mb-3">إضافة امتحان</h5>
    <form method="POST" action="{{ route('exams.store') }}">
      @include('exams._form')
    </form>
  </div>
</div>













@endsection
