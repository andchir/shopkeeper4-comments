import {NgModule} from '@angular/core';
import {APP_BASE_HREF, CommonModule} from '@angular/common';

import {SharedModule} from '@app/shared.module';
import {AppRoutingModule} from './app-routing.module';
import {AppCommentsComponent} from './app.component';
import {DefaultComponent} from './default/default.component';
import {HomeComponent} from './home/home.component';
import {ModalCommentComponent} from './home/modal-comment.component';

@NgModule({
    imports: [
        CommonModule,
        SharedModule,
        AppRoutingModule
    ],
    declarations: [
        AppCommentsComponent,
        DefaultComponent,
        HomeComponent,
        ModalCommentComponent
    ],
    entryComponents: [
        ModalCommentComponent
    ],
    providers: [{
        provide: APP_BASE_HREF, useValue: ''
    }],
    bootstrap: [AppCommentsComponent]
})
export class AppModule {
}
