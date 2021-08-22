<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 4/6/17
 * Time: 3:02 PM
 */
?>
@extends('layouts.master')
@section('content')



    <style>
        /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
        .row.content {height: 550px}

        .my-container-fluid{
            background-color: #fff4c2;
        }

        /* On small screens, set height to 'auto' for the grid */
        @media screen and (max-width: 767px) {
            .row.content {height: auto;}
        }

        #my-right-menu ul{
            background-color: #ff7c7c;
        }

        #my-right-menu ul li {
            background-color: #8d504d;
        }

        #my-right-menu ul li a{
            color: #fff;
            padding: 5px;
        }
        #left-side {
            height: 650px;
            border-right: thin solid black;
        }
        .submenu{
            margin-left: auto;
            margin-right: auto;
            width: 200px;
            background-color: #c2ecc8;
        }
        .submenu h4{
            background-color: #004f3f;
            color: #FFFFFF;
            font-weight: 900;
            text-align: center;
        }
        .submenu ul li a{
            color: #000000;
        }
        .top-blank{
            height: 200px;
        }
    </style>


    <div class="content-wrapper">
        <div class="my-container-fluid">
            <div class="row">
                <div id="left-side" class="col-sm-5">
                    <div class="row ">
                        <div class="col-sm-12">
                            <div class="">
                                <div style="padding-top: 20px; text-align: center" class="col-sm-12">
                                    <div class="col-sm-6">current period<br>&nbsp;2018-2017</div>
                                    <div class="col-sm-6">Current Date<br>&nbsp;12-07-2017</div>
                                </div>
                                <div style="padding-top: 100px; text-align: center; text-decoration: underline; font-weight: 900">List of company</div>

                                <div style="padding-top: 20px" class="col-sm-12">
                                    <table style="width: 100%">
                                        <thead>
                                        <tr>
                                            <td style="text-align: center">Company Name</td>
                                            <td style="text-align: center">Last entry</td>
                                        </tr>
                                        </thead>
                                        <tbody style="font-style: italic">
                                        <tr>
                                            <td style="text-align: left" >company</td>
                                            <td style="text-align: right">12-07-2017</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left" >company</td>
                                            <td style="text-align: right">12-07-2017</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left" >company</td>
                                            <td style="text-align: right">12-07-2017</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="top-blank"></div>
                            <div class="submenu">
                                <h4>Users</h4>
                                <ul class="nav nav-stacked">
                                    <li><a href="">button</a></li>
                                    <li><a href="">button</a></li>
                                    <li><a href="">button</a></li>
                                    <li><a href="">button</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="my-right-menu" class="col-sm-2 hidden-xs">
                    <!--<h2>Logo</h2>-->
                    <ul class="nav nav-stacked">
                        <li><a href="#section1">Dashboard</a></li>
                        <li><a href="#section2">Age</a></li>
                        <li><a href="#section3">Gender</a></li>
                        <li><a href="#section3">Geo</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                        <li><a href="#section3">&nbsp;</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection