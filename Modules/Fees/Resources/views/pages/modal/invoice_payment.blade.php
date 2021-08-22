
<style>
    .font12px {
        font-size: 12px;
    }

    body.stop-scrolling {
        height: 100%;
        overflow: hidden; }

    .sweet-overlay {
        background-color: black;
        /* IE8 */
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=40)";
        /* IE8 */
        background-color: rgba(0, 0, 0, 0.4);
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        display: none;
        z-index: 10000; }

    .sweet-alert {
        background-color: white;
        font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        width: 478px;
        padding: 17px;
        border-radius: 5px;
        text-align: center;
        position: fixed;
        left: 50%;
        top: 50%;
        margin-left: -256px;
        margin-top: -200px;
        overflow: hidden;
        display: none;
        z-index: 99999; }
    @media all and (max-width: 540px) {
        .sweet-alert {
            width: auto;
            margin-left: 0;
            margin-right: 0;
            left: 15px;
            right: 15px; } }
    .sweet-alert h2 {
        color: #575757;
        font-size: 30px;
        text-align: center;
        font-weight: 600;
        text-transform: none;
        position: relative;
        margin: 25px 0;
        padding: 0;
        line-height: 40px;
        display: block; }
    .sweet-alert p {
        color: #797979;
        font-size: 16px;
        text-align: center;
        font-weight: 300;
        position: relative;
        text-align: inherit;
        float: none;
        margin: 0;
        padding: 0;
        line-height: normal; }
    .sweet-alert fieldset {
        border: none;
        position: relative; }
    .sweet-alert .sa-error-container {
        background-color: #f1f1f1;
        margin-left: -17px;
        margin-right: -17px;
        overflow: hidden;
        padding: 0 10px;
        max-height: 0;
        webkit-transition: padding 0.15s, max-height 0.15s;
        transition: padding 0.15s, max-height 0.15s; }
    .sweet-alert .sa-error-container.show {
        padding: 10px 0;
        max-height: 100px;
        webkit-transition: padding 0.2s, max-height 0.2s;
        transition: padding 0.25s, max-height 0.25s; }
    .sweet-alert .sa-error-container .icon {
        display: inline-block;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: #ea7d7d;
        color: white;
        line-height: 24px;
        text-align: center;
        margin-right: 3px; }
    .sweet-alert .sa-error-container p {
        display: inline-block; }
    .sweet-alert .sa-input-error {
        position: absolute;
        top: 29px;
        right: 26px;
        width: 20px;
        height: 20px;
        opacity: 0;
        -webkit-transform: scale(0.5);
        transform: scale(0.5);
        -webkit-transform-origin: 50% 50%;
        transform-origin: 50% 50%;
        -webkit-transition: all 0.1s;
        transition: all 0.1s; }
    .sweet-alert .sa-input-error::before, .sweet-alert .sa-input-error::after {
        content: "";
        width: 20px;
        height: 6px;
        background-color: #f06e57;
        border-radius: 3px;
        position: absolute;
        top: 50%;
        margin-top: -4px;
        left: 50%;
        margin-left: -9px; }
    .sweet-alert .sa-input-error::before {
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg); }
    .sweet-alert .sa-input-error::after {
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg); }
    .sweet-alert .sa-input-error.show {
        opacity: 1;
        -webkit-transform: scale(1);
        transform: scale(1); }
    .sweet-alert input {
        width: 100%;
        box-sizing: border-box;
        border-radius: 3px;
        border: 1px solid #d7d7d7;
        height: 43px;
        margin-top: 10px;
        margin-bottom: 17px;
        font-size: 18px;
        box-shadow: inset 0px 1px 1px rgba(0, 0, 0, 0.06);
        padding: 0 12px;
        display: none;
        -webkit-transition: all 0.3s;
        transition: all 0.3s; }
    .sweet-alert input:focus {
        outline: none;
        box-shadow: 0px 0px 3px #c4e6f5;
        border: 1px solid #b4dbed; }
    .sweet-alert input:focus::-moz-placeholder {
        transition: opacity 0.3s 0.03s ease;
        opacity: 0.5; }
    .sweet-alert input:focus:-ms-input-placeholder {
        transition: opacity 0.3s 0.03s ease;
        opacity: 0.5; }
    .sweet-alert input:focus::-webkit-input-placeholder {
        transition: opacity 0.3s 0.03s ease;
        opacity: 0.5; }
    .sweet-alert input::-moz-placeholder {
        color: #bdbdbd; }
    .sweet-alert input::-ms-clear {
        display: none; }
    .sweet-alert input:-ms-input-placeholder {
        color: #bdbdbd; }
    .sweet-alert input::-webkit-input-placeholder {
        color: #bdbdbd; }
    .sweet-alert.show-input input {
        display: block; }
    .sweet-alert .sa-confirm-button-container {
        display: inline-block;
        position: relative; }
    .sweet-alert .la-ball-fall {
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -27px;
        margin-top: 4px;
        opacity: 0;
        visibility: hidden; }
    .sweet-alert button {
        background-color: #8CD4F5;
        color: white;
        border: none;
        box-shadow: none;
        font-size: 17px;
        font-weight: 500;
        -webkit-border-radius: 4px;
        border-radius: 5px;
        padding: 10px 32px;
        margin: 26px 5px 0 5px;
        cursor: pointer; }
    .sweet-alert button:focus {
        outline: none;
        box-shadow: 0 0 2px rgba(128, 179, 235, 0.5), inset 0 0 0 1px rgba(0, 0, 0, 0.05); }
    .sweet-alert button:hover {
        background-color: #7ecff4; }
    .sweet-alert button:active {
        background-color: #5dc2f1; }
    .sweet-alert button.cancel {
        background-color: #C1C1C1; }
    .sweet-alert button.cancel:hover {
        background-color: #b9b9b9; }
    .sweet-alert button.cancel:active {
        background-color: #a8a8a8; }
    .sweet-alert button.cancel:focus {
        box-shadow: rgba(197, 205, 211, 0.8) 0px 0px 2px, rgba(0, 0, 0, 0.0470588) 0px 0px 0px 1px inset !important; }
    .sweet-alert button[disabled] {
        opacity: .6;
        cursor: default; }
    .sweet-alert button.confirm[disabled] {
        color: transparent; }
    .sweet-alert button.confirm[disabled] ~ .la-ball-fall {
        opacity: 1;
        visibility: visible;
        transition-delay: 0s; }
    .sweet-alert button::-moz-focus-inner {
        border: 0; }
    .sweet-alert[data-has-cancel-button=false] button {
        box-shadow: none !important; }
    .sweet-alert[data-has-confirm-button=false][data-has-cancel-button=false] {
        padding-bottom: 40px; }
    .sweet-alert .sa-icon {
        width: 80px;
        height: 80px;
        border: 4px solid gray;
        -webkit-border-radius: 40px;
        border-radius: 40px;
        border-radius: 50%;
        margin: 20px auto;
        padding: 0;
        position: relative;
        box-sizing: content-box; }
    .sweet-alert .sa-icon.sa-error {
        border-color: #F27474; }
    .sweet-alert .sa-icon.sa-error .sa-x-mark {
        position: relative;
        display: block; }
    .sweet-alert .sa-icon.sa-error .sa-line {
        position: absolute;
        height: 5px;
        width: 47px;
        background-color: #F27474;
        display: block;
        top: 37px;
        border-radius: 2px; }
    .sweet-alert .sa-icon.sa-error .sa-line.sa-left {
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
        left: 17px; }
    .sweet-alert .sa-icon.sa-error .sa-line.sa-right {
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg);
        right: 16px; }
    .sweet-alert .sa-icon.sa-warning {
        border-color: #F8BB86; }
    .sweet-alert .sa-icon.sa-warning .sa-body {
        position: absolute;
        width: 5px;
        height: 47px;
        left: 50%;
        top: 10px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        margin-left: -2px;
        background-color: #F8BB86; }
    .sweet-alert .sa-icon.sa-warning .sa-dot {
        position: absolute;
        width: 7px;
        height: 7px;
        -webkit-border-radius: 50%;
        border-radius: 50%;
        margin-left: -3px;
        left: 50%;
        bottom: 10px;
        background-color: #F8BB86; }
    .sweet-alert .sa-icon.sa-info {
        border-color: #C9DAE1; }
    .sweet-alert .sa-icon.sa-info::before {
        content: "";
        position: absolute;
        width: 5px;
        height: 29px;
        left: 50%;
        bottom: 17px;
        border-radius: 2px;
        margin-left: -2px;
        background-color: #C9DAE1; }
    .sweet-alert .sa-icon.sa-info::after {
        content: "";
        position: absolute;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        margin-left: -3px;
        top: 19px;
        background-color: #C9DAE1;
        left: 50%; }
    .sweet-alert .sa-icon.sa-success {
        border-color: #A5DC86; }
    .sweet-alert .sa-icon.sa-success::before, .sweet-alert .sa-icon.sa-success::after {
        content: '';
        -webkit-border-radius: 40px;
        border-radius: 40px;
        border-radius: 50%;
        position: absolute;
        width: 60px;
        height: 120px;
        background: white;
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg); }
    .sweet-alert .sa-icon.sa-success::before {
        -webkit-border-radius: 120px 0 0 120px;
        border-radius: 120px 0 0 120px;
        top: -7px;
        left: -33px;
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg);
        -webkit-transform-origin: 60px 60px;
        transform-origin: 60px 60px; }
    .sweet-alert .sa-icon.sa-success::after {
        -webkit-border-radius: 0 120px 120px 0;
        border-radius: 0 120px 120px 0;
        top: -11px;
        left: 30px;
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg);
        -webkit-transform-origin: 0px 60px;
        transform-origin: 0px 60px; }
    .sweet-alert .sa-icon.sa-success .sa-placeholder {
        width: 80px;
        height: 80px;
        border: 4px solid rgba(165, 220, 134, 0.2);
        -webkit-border-radius: 40px;
        border-radius: 40px;
        border-radius: 50%;
        box-sizing: content-box;
        position: absolute;
        left: -4px;
        top: -4px;
        z-index: 2; }
    .sweet-alert .sa-icon.sa-success .sa-fix {
        width: 5px;
        height: 90px;
        background-color: white;
        position: absolute;
        left: 28px;
        top: 8px;
        z-index: 1;
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg); }
    .sweet-alert .sa-icon.sa-success .sa-line {
        height: 5px;
        background-color: #A5DC86;
        display: block;
        border-radius: 2px;
        position: absolute;
        z-index: 2; }
    .sweet-alert .sa-icon.sa-success .sa-line.sa-tip {
        width: 25px;
        left: 14px;
        top: 46px;
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg); }
    .sweet-alert .sa-icon.sa-success .sa-line.sa-long {
        width: 47px;
        right: 8px;
        top: 38px;
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg); }
    .sweet-alert .sa-icon.sa-custom {
        background-size: contain;
        border-radius: 0;
        border: none;
        background-position: center center;
        background-repeat: no-repeat; }

    /*
     * Animations
     */
    @-webkit-keyframes showSweetAlert {
        0% {
            transform: scale(0.7);
            -webkit-transform: scale(0.7); }
        45% {
            transform: scale(1.05);
            -webkit-transform: scale(1.05); }
        80% {
            transform: scale(0.95);
            -webkit-transform: scale(0.95); }
        100% {
            transform: scale(1);
            -webkit-transform: scale(1); } }

    @keyframes showSweetAlert {
        0% {
            transform: scale(0.7);
            -webkit-transform: scale(0.7); }
        45% {
            transform: scale(1.05);
            -webkit-transform: scale(1.05); }
        80% {
            transform: scale(0.95);
            -webkit-transform: scale(0.95); }
        100% {
            transform: scale(1);
            -webkit-transform: scale(1); } }

    @-webkit-keyframes hideSweetAlert {
        0% {
            transform: scale(1);
            -webkit-transform: scale(1); }
        100% {
            transform: scale(0.5);
            -webkit-transform: scale(0.5); } }

    @keyframes hideSweetAlert {
        0% {
            transform: scale(1);
            -webkit-transform: scale(1); }
        100% {
            transform: scale(0.5);
            -webkit-transform: scale(0.5); } }

    @-webkit-keyframes slideFromTop {
        0% {
            top: 0%; }
        100% {
            top: 50%; } }

    @keyframes slideFromTop {
        0% {
            top: 0%; }
        100% {
            top: 50%; } }

    @-webkit-keyframes slideToTop {
        0% {
            top: 50%; }
        100% {
            top: 0%; } }

    @keyframes slideToTop {
        0% {
            top: 50%; }
        100% {
            top: 0%; } }

    @-webkit-keyframes slideFromBottom {
        0% {
            top: 70%; }
        100% {
            top: 50%; } }

    @keyframes slideFromBottom {
        0% {
            top: 70%; }
        100% {
            top: 50%; } }

    @-webkit-keyframes slideToBottom {
        0% {
            top: 50%; }
        100% {
            top: 70%; } }

    @keyframes slideToBottom {
        0% {
            top: 50%; }
        100% {
            top: 70%; } }

    .showSweetAlert[data-animation=pop] {
        -webkit-animation: showSweetAlert 0.3s;
        animation: showSweetAlert 0.3s; }

    .showSweetAlert[data-animation=none] {
        -webkit-animation: none;
        animation: none; }

    .showSweetAlert[data-animation=slide-from-top] {
        -webkit-animation: slideFromTop 0.3s;
        animation: slideFromTop 0.3s; }

    .showSweetAlert[data-animation=slide-from-bottom] {
        -webkit-animation: slideFromBottom 0.3s;
        animation: slideFromBottom 0.3s; }

    .hideSweetAlert[data-animation=pop] {
        -webkit-animation: hideSweetAlert 0.2s;
        animation: hideSweetAlert 0.2s; }

    .hideSweetAlert[data-animation=none] {
        -webkit-animation: none;
        animation: none; }

    .hideSweetAlert[data-animation=slide-from-top] {
        -webkit-animation: slideToTop 0.4s;
        animation: slideToTop 0.4s; }

    .hideSweetAlert[data-animation=slide-from-bottom] {
        -webkit-animation: slideToBottom 0.3s;
        animation: slideToBottom 0.3s; }

    @-webkit-keyframes animateSuccessTip {
        0% {
            width: 0;
            left: 1px;
            top: 19px; }
        54% {
            width: 0;
            left: 1px;
            top: 19px; }
        70% {
            width: 50px;
            left: -8px;
            top: 37px; }
        84% {
            width: 17px;
            left: 21px;
            top: 48px; }
        100% {
            width: 25px;
            left: 14px;
            top: 45px; } }

    @keyframes animateSuccessTip {
        0% {
            width: 0;
            left: 1px;
            top: 19px; }
        54% {
            width: 0;
            left: 1px;
            top: 19px; }
        70% {
            width: 50px;
            left: -8px;
            top: 37px; }
        84% {
            width: 17px;
            left: 21px;
            top: 48px; }
        100% {
            width: 25px;
            left: 14px;
            top: 45px; } }

    @-webkit-keyframes animateSuccessLong {
        0% {
            width: 0;
            right: 46px;
            top: 54px; }
        65% {
            width: 0;
            right: 46px;
            top: 54px; }
        84% {
            width: 55px;
            right: 0px;
            top: 35px; }
        100% {
            width: 47px;
            right: 8px;
            top: 38px; } }

    @keyframes animateSuccessLong {
        0% {
            width: 0;
            right: 46px;
            top: 54px; }
        65% {
            width: 0;
            right: 46px;
            top: 54px; }
        84% {
            width: 55px;
            right: 0px;
            top: 35px; }
        100% {
            width: 47px;
            right: 8px;
            top: 38px; } }

    @-webkit-keyframes rotatePlaceholder {
        0% {
            transform: rotate(-45deg);
            -webkit-transform: rotate(-45deg); }
        5% {
            transform: rotate(-45deg);
            -webkit-transform: rotate(-45deg); }
        12% {
            transform: rotate(-405deg);
            -webkit-transform: rotate(-405deg); }
        100% {
            transform: rotate(-405deg);
            -webkit-transform: rotate(-405deg); } }

    @keyframes rotatePlaceholder {
        0% {
            transform: rotate(-45deg);
            -webkit-transform: rotate(-45deg); }
        5% {
            transform: rotate(-45deg);
            -webkit-transform: rotate(-45deg); }
        12% {
            transform: rotate(-405deg);
            -webkit-transform: rotate(-405deg); }
        100% {
            transform: rotate(-405deg);
            -webkit-transform: rotate(-405deg); } }

    .animateSuccessTip {
        -webkit-animation: animateSuccessTip 0.75s;
        animation: animateSuccessTip 0.75s; }

    .animateSuccessLong {
        -webkit-animation: animateSuccessLong 0.75s;
        animation: animateSuccessLong 0.75s; }

    .sa-icon.sa-success.animate::after {
        -webkit-animation: rotatePlaceholder 4.25s ease-in;
        animation: rotatePlaceholder 4.25s ease-in; }

    @-webkit-keyframes animateErrorIcon {
        0% {
            transform: rotateX(100deg);
            -webkit-transform: rotateX(100deg);
            opacity: 0; }
        100% {
            transform: rotateX(0deg);
            -webkit-transform: rotateX(0deg);
            opacity: 1; } }

    @keyframes animateErrorIcon {
        0% {
            transform: rotateX(100deg);
            -webkit-transform: rotateX(100deg);
            opacity: 0; }
        100% {
            transform: rotateX(0deg);
            -webkit-transform: rotateX(0deg);
            opacity: 1; } }

    .animateErrorIcon {
        -webkit-animation: animateErrorIcon 0.5s;
        animation: animateErrorIcon 0.5s; }

    @-webkit-keyframes animateXMark {
        0% {
            transform: scale(0.4);
            -webkit-transform: scale(0.4);
            margin-top: 26px;
            opacity: 0; }
        50% {
            transform: scale(0.4);
            -webkit-transform: scale(0.4);
            margin-top: 26px;
            opacity: 0; }
        80% {
            transform: scale(1.15);
            -webkit-transform: scale(1.15);
            margin-top: -6px; }
        100% {
            transform: scale(1);
            -webkit-transform: scale(1);
            margin-top: 0;
            opacity: 1; } }

    @keyframes animateXMark {
        0% {
            transform: scale(0.4);
            -webkit-transform: scale(0.4);
            margin-top: 26px;
            opacity: 0; }
        50% {
            transform: scale(0.4);
            -webkit-transform: scale(0.4);
            margin-top: 26px;
            opacity: 0; }
        80% {
            transform: scale(1.15);
            -webkit-transform: scale(1.15);
            margin-top: -6px; }
        100% {
            transform: scale(1);
            -webkit-transform: scale(1);
            margin-top: 0;
            opacity: 1; } }

    .animateXMark {
        -webkit-animation: animateXMark 0.5s;
        animation: animateXMark 0.5s; }

    @-webkit-keyframes pulseWarning {
        0% {
            border-color: #F8D486; }
        100% {
            border-color: #F8BB86; } }

    @keyframes pulseWarning {
        0% {
            border-color: #F8D486; }
        100% {
            border-color: #F8BB86; } }

    .pulseWarning {
        -webkit-animation: pulseWarning 0.75s infinite alternate;
        animation: pulseWarning 0.75s infinite alternate; }

    @-webkit-keyframes pulseWarningIns {
        0% {
            background-color: #F8D486; }
        100% {
            background-color: #F8BB86; } }

    @keyframes pulseWarningIns {
        0% {
            background-color: #F8D486; }
        100% {
            background-color: #F8BB86; } }

    .pulseWarningIns {
        -webkit-animation: pulseWarningIns 0.75s infinite alternate;
        animation: pulseWarningIns 0.75s infinite alternate; }

    @-webkit-keyframes rotate-loading {
        0% {
            transform: rotate(0deg); }
        100% {
            transform: rotate(360deg); } }

    @keyframes rotate-loading {
        0% {
            transform: rotate(0deg); }
        100% {
            transform: rotate(360deg); } }

    /* Internet Explorer 9 has some special quirks that are fixed here */
    /* The icons are not animated. */
    /* This file is automatically merged into sweet-alert.min.js through Gulp */
    /* Error icon */
    .sweet-alert .sa-icon.sa-error .sa-line.sa-left {
        -ms-transform: rotate(45deg) \9; }

    .sweet-alert .sa-icon.sa-error .sa-line.sa-right {
        -ms-transform: rotate(-45deg) \9; }

    /* Success icon */
    .sweet-alert .sa-icon.sa-success {
        border-color: transparent\9; }

    .sweet-alert .sa-icon.sa-success .sa-line.sa-tip {
        -ms-transform: rotate(45deg) \9; }

    .sweet-alert .sa-icon.sa-success .sa-line.sa-long {
        -ms-transform: rotate(-45deg) \9; }

    /*!
     * Load Awesome v1.1.0 (http://github.danielcardoso.net/load-awesome/)
     * Copyright 2015 Daniel Cardoso <@DanielCardoso>
 * Licensed under MIT
 */
    .la-ball-fall,
    .la-ball-fall > div {
        position: relative;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box; }

    .la-ball-fall {
        display: block;
        font-size: 0;
        color: #fff; }

    .la-ball-fall.la-dark {
        color: #333; }

    .la-ball-fall > div {
        display: inline-block;
        float: none;
        background-color: currentColor;
        border: 0 solid currentColor; }

    .la-ball-fall {
        width: 54px;
        height: 18px; }

    .la-ball-fall > div {
        width: 10px;
        height: 10px;
        margin: 4px;
        border-radius: 100%;
        opacity: 0;
        -webkit-animation: ball-fall 1s ease-in-out infinite;
        -moz-animation: ball-fall 1s ease-in-out infinite;
        -o-animation: ball-fall 1s ease-in-out infinite;
        animation: ball-fall 1s ease-in-out infinite; }

    .la-ball-fall > div:nth-child(1) {
        -webkit-animation-delay: -200ms;
        -moz-animation-delay: -200ms;
        -o-animation-delay: -200ms;
        animation-delay: -200ms; }

    .la-ball-fall > div:nth-child(2) {
        -webkit-animation-delay: -100ms;
        -moz-animation-delay: -100ms;
        -o-animation-delay: -100ms;
        animation-delay: -100ms; }

    .la-ball-fall > div:nth-child(3) {
        -webkit-animation-delay: 0ms;
        -moz-animation-delay: 0ms;
        -o-animation-delay: 0ms;
        animation-delay: 0ms; }

    .la-ball-fall.la-sm {
        width: 26px;
        height: 8px; }

    .la-ball-fall.la-sm > div {
        width: 4px;
        height: 4px;
        margin: 2px; }

    .la-ball-fall.la-2x {
        width: 108px;
        height: 36px; }

    .la-ball-fall.la-2x > div {
        width: 20px;
        height: 20px;
        margin: 8px; }

    .la-ball-fall.la-3x {
        width: 162px;
        height: 54px; }

    .la-ball-fall.la-3x > div {
        width: 30px;
        height: 30px;
        margin: 12px; }

    /*
     * Animation
     */
    @-webkit-keyframes ball-fall {
        0% {
            opacity: 0;
            -webkit-transform: translateY(-145%);
            transform: translateY(-145%); }
        10% {
            opacity: .5; }
        20% {
            opacity: 1;
            -webkit-transform: translateY(0);
            transform: translateY(0); }
        80% {
            opacity: 1;
            -webkit-transform: translateY(0);
            transform: translateY(0); }
        90% {
            opacity: .5; }
        100% {
            opacity: 0;
            -webkit-transform: translateY(145%);
            transform: translateY(145%); } }

    @-moz-keyframes ball-fall {
        0% {
            opacity: 0;
            -moz-transform: translateY(-145%);
            transform: translateY(-145%); }
        10% {
            opacity: .5; }
        20% {
            opacity: 1;
            -moz-transform: translateY(0);
            transform: translateY(0); }
        80% {
            opacity: 1;
            -moz-transform: translateY(0);
            transform: translateY(0); }
        90% {
            opacity: .5; }
        100% {
            opacity: 0;
            -moz-transform: translateY(145%);
            transform: translateY(145%); } }

    @-o-keyframes ball-fall {
        0% {
            opacity: 0;
            -o-transform: translateY(-145%);
            transform: translateY(-145%); }
        10% {
            opacity: .5; }
        20% {
            opacity: 1;
            -o-transform: translateY(0);
            transform: translateY(0); }
        80% {
            opacity: 1;
            -o-transform: translateY(0);
            transform: translateY(0); }
        90% {
            opacity: .5; }
        100% {
            opacity: 0;
            -o-transform: translateY(145%);
            transform: translateY(145%); } }

    @keyframes ball-fall {
        0% {
            opacity: 0;
            -webkit-transform: translateY(-145%);
            -moz-transform: translateY(-145%);
            -o-transform: translateY(-145%);
            transform: translateY(-145%); }
        10% {
            opacity: .5; }
        20% {
            opacity: 1;
            -webkit-transform: translateY(0);
            -moz-transform: translateY(0);
            -o-transform: translateY(0);
            transform: translateY(0); }
        80% {
            opacity: 1;
            -webkit-transform: translateY(0);
            -moz-transform: translateY(0);
            -o-transform: translateY(0);
            transform: translateY(0); }
        90% {
            opacity: .5; }
        100% {
            opacity: 0;
            -webkit-transform: translateY(145%);
            -moz-transform: translateY(145%);
            -o-transform: translateY(145%);
            transform: translateY(145%); } }

    label.col-md-5.control-label {
        font-size: 14px;
    }
</style>


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title" id="gsmTitle">Payment Transaction Details</h4>
</div>

@php
    $fees=$invoiceInfo->fees();

@endphp

<div class="container">
<div class="col-md-12">

    {{--action="/fees/invoice/payment/student/store"--}}
    <form  id="InvoicePaymentForm" action="/fees/invoice/payment/student/store"  class="form-horizontal margin-none" style="margin-top: 20px;" method="post" >
        <div class="col-md-5">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="fees_id" value="{{$invoiceInfo->fees_id}}">
        <input type="hidden" name="invoice_id" value="{{$invoiceInfo->id}}">
        <input type="hidden" name="payer_id" value="{{$invoiceInfo->payer_id}}">
        <input type="hidden" name="payment_id" value="@if(!empty($paymentInfo)) {{$paymentInfo->id}} @endif">
        {{--<input type="hidden" id="partial_allowed" name="partial_allowed" value="{{$fees->partial_allowed}}">--}}
        {{--<input type="hidden" name="total_extra_amount"  @if(!empty($stdExtraAmount)) value="{{$stdExtraAmount}}" @else value="0" @endif>--}}
        <div class="separator bottom"></div>
        {{--<div class="form-group">--}}
            {{--<label class="col-md-5 control-label" for="firstname">Partial Allow</label>--}}
            {{--<div class="col-md-6">@if($fees->partial_allowed==1) Yes @else No @endif</div>--}}
        {{--</div>--}}
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Due Amount</label>
            <div class="col-md-6">
             @if($invoiceInfo->fees_id!=NULL)
                @php $subtotal=0; $totalPaymentAmount=0; $totalAmount=0; $totalDiscount=0; $getDueFine=0;  @endphp
                @foreach($fees->feesItems() as $amount)
                    @php $subtotal += $amount->rate*$amount->qty;@endphp

                @endforeach

                @if($paymentList)
                    @foreach($paymentList as $payment)
                        @php $totalPaymentAmount += $payment->payment_amount;@endphp
                    @endforeach
                @else
                @endif



                @if(!empty($invoiceInfo->findReduction()))
                    @php $getDueFine=$invoiceInfo->findReduction()->due_fine; @endphp
                @else
                    @php $getDueFine=get_fees_day_amount($invoiceInfo->fees()->due_date) @endphp
                @endif

                    @if($discount = $invoiceInfo->fees()->discount())
                        @php $discountPercent=$discount->discount_percent;
                                                    $totalDiscount=(($subtotal*$discountPercent)/100);
                                                    $totalAmount=$subtotal-$totalDiscount
                        @endphp
                    @else
                        @php
                            $totalAmount=$subtotal;
                        @endphp

                    @endif


                    {{--waiver Check --}}
                    @if($invoiceInfo->waiver_type=="1")
                        @php $totalWaiver=(($totalAmount*$invoiceInfo->waiver_fees)/100);
                                                 $totalAmount=$totalAmount-$totalWaiver
                        @endphp
                    @elseif($invoiceInfo->waiver_type=="2")
                        @php $totalWaiver=$invoiceInfo->waiver_fees;
                                                 $totalAmount=$totalAmount-$totalWaiver
                        @endphp

                    @endif


                    @if($discount = $invoiceInfo->fees()->discount())
                        @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
                    @endif


                    @if(!empty($invoiceInfo->waiver_fees))
                        @php $totalDiscount=$totalDiscount+$totalWaiver @endphp
                    @endif


                    @php
                    $totalAmount=$subtotal-$totalDiscount;
                @endphp




                @if($totalPaymentAmount>$totalAmount)
                    <span style="display: block; width: 100%; height: 34px; padding: 6px 12px; font-size: 14px; color: #555555; background-color: #eee;">0</span>
                    <input type="hidden" name="due_amount" value="0">
                @else
                    <span style="display: block; width: 100%; height: 34px; padding: 6px 12px; font-size: 14px; color: #555555; background-color: #eee;">{{$totalAmount-$totalPaymentAmount+$getDueFine}}</span>
                    <input type="hidden" class="due_amount" name="due_amount" value="{{$totalAmount-$totalPaymentAmount+$getDueFine}}">

                @endif

                    <input type="hidden" name="fine_amount" value="{{$getDueFine}}">
                    <input type="hidden" name="attendance_fine" value="">

                    <input type="hidden" id="Feessubtotal" name="total_amount" value="{{$totalAmount+$getDueFine}}">


                @else
                    @php $getDueFine=$invoiceInfo->invoice_amount @endphp

                    <span style="display: block; width: 100%; height: 34px; padding: 6px 12px; font-size: 14px; color: #555555; background-color: #eee;">{{$getDueFine}}</span>
                    <input type="hidden" class="due_amount" name="due_amount" value="{{$getDueFine}}">

                    <input type="hidden" name="fine_amount" value="{{$getDueFine}}">
                    <input type="hidden" id="Feessubtotal" name="total_amount" value="{{$getDueFine}}">


                @endif

                {{--@else--}}
                {{--<input type="hidden" id="Feessubtotal" name="total_amount" value="{{$subtotal}}">--}}
                {{--<span style="display: block; width: 100%; height: 34px; padding: 6px 12px; font-size: 14px; color: #555555; background-color: #eee;">--}}

                {{--@if($totalPaymentAmount>$subtotal)--}}
                {{--0--}}
                {{--<input type="hidden" class="due_amount" name="due_amount" value="0">--}}

                {{--@else--}}
                {{--{{$subtotal-$totalPaymentAmount}}--}}
                {{--<input type="hidden" class="due_amount" name="due_amount" value="{{$subtotal-$totalPaymentAmount}}">--}}

                {{--@endif--}}

                {{--</span>--}}


            </div>
        </div>

        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Payment Amount</label>
            <div class="col-md-6">
                <input name="payment_amount"  @if(!empty($paymentInfo->payment_amount)) value="{{$paymentInfo->payment_amount}}" @else value="" @endif id="payment_amount" class="form-control" placeholder="Payment Amount in BDT" step="any" maxlength="13" type="number">                                <p id="paid_amount_error" class="has-error help-block" style="display: none;">Please enter valid payment amount.</p>
                <p id="Payment_amount_message" class="has-error help-block" style="display: none;">Please Pay Your Total Fees or More Amount</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Payment Through</label>
            <div class="col-md-6">
                <select name="payment_method" id="paid_through"  required class="form-control">
                    @foreach($paymentMethodList as $paymentMethod)
                        @if(!empty($paymentInfo->payment_method_id))
                            <option @if($paymentMethod->id==$paymentInfo->payment_method_id) selected="selected" @endif value="{{$paymentMethod->id}}">{{$paymentMethod->method_name}}</option>
                        @else
                            <option value="{{$paymentMethod->id}}">{{$paymentMethod->method_name}}</option>
                        @endif
                    @endforeach

                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Transaction Id/Cheque No.</label>
            <div class="col-md-6">
                <input name="transaction_id"   id="transaction_id" readonly  @if(!empty($paymentInfo->transaction_id)) value="{{$paymentInfo->transaction_id}}" @else value="{{$transactionId}}" @endif class="form-control" placeholder="Transaction Id/Cheque No." type="text">                                <p id="transaction_id_error" class="has-error help-block" style="display: none;">Please enter transaction id/cheque no.</p>
                <p id="correctTransIdConfirm" class="text-warning hidden" style="font-size:12px;">Please ensure that correct transaction ID is entered. If any entry is incorrect then auto update of payment status will stop working.</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Payment Date</label>
            <div class="col-md-6">
                <input name="payment_date" required @if(!empty($paymentInfo->payment_date)) value="{{$paymentInfo->payment_date}}" @endif id="PaymentDate" class="form-control" placeholder="Paid On" type="text">                                <p id="paid_on_error" class="has-error help-block" style="display: none;">Please enter paid on date.</p>
                <p id="late_charges_error" class="has-error help-block" style="display: none;">Due date is passed away so user must pay all remaining payment(+ late charges) in a single transaction.</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label"  for="firstname">Paid By</label>
            <div class="col-md-6">
                <select required name="paid_by" id="paid_by" class="form-control">
                    <option  @if(!empty($paymentInfo->paid_by)=="student") selected="selected" @endif value="student">Student</option>
                    <option  @if(!empty($paymentInfo->paid_by)=="parents") selected="selected" @endif value="parents">Parent</option>
                </select>
            </div>
        </div>

        @if($feesModule)
            <div class="form-group">
                <label class="col-md-5 control-label" for="firstname">Send Automatic SMS</label>
                <div class="col-md-6">
                    <input name="automatic_sms" type="checkbox" value="1">

                </div>
            </div>
        @endif

        {{--<div class="form-group">--}}
            {{--<label class="col-md-5 control-label" for="firstname"></label>--}}
            {{--<div class="col-md-6">--}}
                {{--<input type="hidden" name="data[FeesPayment][send_notification_emails]" id="send_notification_emails_" value="0"><input type="checkbox" name="data[FeesPayment][send_notification_emails]" class="form-control1" id="send_notification_emails" value="1">--}}
                {{--<label for="send_notification_emails">Send Notification Emails</label>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</form>--}}
</div>

    <div class="col-md-4 full-right">

        <div class="form-horizontal">
            <div class="panel panel-default">
                <div class="panel-body">Student Advance Amount Panel</div>
            </div>
            @php $sumDumeAmount=$totalAmount-$totalPaymentAmount+$getDueFine @endphp

            <div class="form-group">
                <label class="col-md-5 control-label" for="firstname">Advance Amount</label>
                <div class="col-md-6">
                    <input name="total_extra_amount" readonly  class="form-control total_extra_amount" type="number" value="{{$stdExtraAmount}}">
                </div>
                <div class="col-md-12" style="padding: 12px">
                @if(($sumDumeAmount>$stdExtraAmount) && ($stdExtraAmount>0))
                    <span class="label font12px label-warning">Your Advance Balance is Low. Need More {{$sumDumeAmount-$stdExtraAmount}}</span>
                @endif
                </div>
            </div>

        @if($stdExtraAmount>0)
         <div class="form-group">
             <label class="col-md-5 control-label" for="use_advance_amount">Use Advance Amount</label>
             <div class="col-md-6">
                 <input name="use_advance_amount" type="checkbox" value="1">
             </div>
         </div>
         @endif

     </div>
 </div>
 <div class="col-md-6 col-md-offset-2" style="padding:20px"> <button id="updateButton" class="btn btn-primary" data-payable-amount="3509">Add</button>
 </div>

 </form>
</div>

</div>
</div>




<script>


 $('#paid_through').on('change', function() {
     // Does some stuff and logs the event to the console
    var payment_method=$(this).val();
    if(payment_method=="2") {
        $('#transaction_id').attr("required", "true");
    } else {
        $('#transaction_id').removeAttr('required');
    }

 });

 {{--//payment date--}}
 $('#PaymentDate').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});

// // request for invoice Payment from
// $('form#InvoicePaymentForm').on('submit', function (e) {
//     e.preventDefault();
//
//     var partial_allow=$('#partial_allowed').val();
//     var fees_total_amount=parseInt($('.due_amount').val());
//     var paymentamount=parseInt($("#payment_amount").val());
//
//
//     if(partial_allow==1) {
//
//             // ajax request
//             invoice_payment_ajax_req();
//     }
//     else {
//         if (paymentamount >=fees_total_amount) {
//             //ajax request
//             invoice_payment_ajax_req();
//         }
//         else {
//             $("#Payment_amount_message").show();
//         }
//     }
//
// });

 //function invoice payment ajax req
 function invoice_payment_ajax_req(){
     @if(!empty($paymentInfo))
     var url= '/fees/invoice/payment/update';
     @elseif(!empty($invoiceInfo->fees_id))
     var url= '/fees/invoice/payment/student/store';
     @else
     var url= '/fees/invoice/attendance/payment/student/store';
     @endif

//       ajax request code
     $.ajax({

         url: url,
         type: 'POST',
         cache: false,
         data: $('form#InvoicePaymentForm').serialize(),
         datatype: 'json/application',

         beforeSend: function() {
//                 alert($('form#InvoicePaymentForm').serialize());
         },

         success:function(data){
                     swal({
                         title: "Paid!",
                         text: "Fees Amount Successfully Paid",
                         icon: "success",
                         button: "Close",
                     });
             setTimeout(function() {
                 window.location.href = "";
             },3000)
             },

         error:function(data){
             alert('error');
         }
     });
 }

 $('#add-extra-amount').click(function(){

     var totalExtraAmount=$('.total_extra_amount').val();
     var dueAmount=$('.due_amount').val();
     var addExtraAmount=$('.get_extra_amount').val();
//        alert(dueAmount);
//        alert(addExtraAmount);

     if(addExtraAmount>dueAmount){
         alert("You can only get due amout in your extra amount");
//            alert("Only Add"+dueAmount+"Tk" );
     } else {

     if(totalExtraAmount>=dueAmount){

      alert(addExtraAmount);
         $('#payment_amount').val(addExtraAmount);
     } else {
         alert(dueAmount);
     }
     }
 });





</script>

