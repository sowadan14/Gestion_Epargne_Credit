@extends('layouts.master')

@section('content')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
              <li>
                <i class="ace-icon glyphicon glyphicon-th-list"></i>
                <a href="#">Tables</a>
              </li>
              <li class="active">Table basique</li>
            </ul><!-- /.breadcrumb -->

            <!-- <div class="nav-search" id="nav-search">
              <form class="form-search">
                <span class="input-icon">
                  <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                  <i class="ace-icon fa fa-search nav-search-icon"></i>
                </span>
              </form>
            </div>/.nav-search -->
          </div>
          <div class="page-content">
            <div class="page-header">
              <h1>
               Liste des états 
                <!-- <small>
                  <i class="ace-icon fa fa-angle-double-right"></i>
                  overview &amp; stats
                </small> -->
              </h1>
            </div><!-- /.page-header -->
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table  table-striped" style="width:100%;">
            <thead>
              <tr>
                <th>Profile</th>
                <th>VatNo.</th>
                <th>Created</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Jacob</td>
                <td>53275531</td>
                <td>12 May 2017</td>
                <td>
                  <label class="badge badge-danger">Pending</label>
                </td>
              </tr>
              <tr>
                <td>Messsy</td>
                <td>53275532</td>
                <td>15 May 2017</td>
                <td>
                  <label class="badge badge-warning">In progress</label>
                </td>
              </tr>
              <tr>
                <td>John</td>
                <td>53275533</td>
                <td>14 May 2017</td>
                <td>
                  <label class="badge badge-info">Fixed</label>
                </td>
              </tr>
              <tr>
                <td>Peter</td>
                <td>53275534</td>
                <td>16 May 2017</td>
                <td>
                  <label class="badge badge-success">Completed</label>
                </td>
              </tr>
              <tr>
                <td>Dave</td>
                <td>53275535</td>
                <td>20 May 2017</td>
                <td>
                  <label class="badge badge-warning">In progress</label>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
 
</div>
</div>
@endsection