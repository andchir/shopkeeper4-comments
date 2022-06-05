import {Component, OnInit} from '@angular/core';

declare const window: Window;

@Component({
    selector: 'app-comments-default',
    templateUrl: './default.component.html'
})
export class DefaultComponent implements OnInit {

    constructor() {
    }

    ngOnInit() {
    }

    navBarToggle(): void {
        window.document.querySelector('.layout-sidebar').classList.toggle('active');
        window.document.querySelector('.layout-mask').classList.toggle('layout-mask-active');
    }
}
