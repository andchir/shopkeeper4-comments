import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {APP_BASE_HREF, CommonModule, registerLocaleData} from '@angular/common';

import {TranslateLoader, TranslateModule} from '@ngx-translate/core';
import {TranslateCustomLoader} from '@app/services/translateLoader';

import {SharedModule} from '@app/shared.module';
import {AppRoutingModule} from './app-routing.module';
import {AppCommentsComponent} from './app.component';
import {DefaultComponent} from './default/default.component';
import {HomeComponent} from './home/home.component';
import {ModalCommentComponent} from './home/modal-comment.component';
import {ModalExportJsonComponent} from '@app/components/modal-export-json';

@NgModule({
    imports: [
        CommonModule,
        BrowserModule,
        BrowserAnimationsModule,
        AppRoutingModule,
        SharedModule,
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useClass: TranslateCustomLoader
            }
        })
    ],
    declarations: [
        AppCommentsComponent,
        DefaultComponent,
        HomeComponent,
        ModalCommentComponent,
        ModalExportJsonComponent
    ],
    entryComponents: [
        ModalCommentComponent,
        ModalExportJsonComponent
    ],
    providers: [{
        provide: APP_BASE_HREF, useValue: ''
    }],
    bootstrap: [AppCommentsComponent]
})
export class AppModule {
}
