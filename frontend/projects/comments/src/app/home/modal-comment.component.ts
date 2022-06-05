import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';

import {DynamicDialogConfig, DynamicDialogRef} from 'primeng/dynamicdialog';
import {TranslateService} from '@ngx-translate/core';

import {AppSettings} from '@app/services/app-settings.service';
import {SystemNameService} from '@app/services/system-name.service';
import {CommentsService} from '../services/comments.service';
import {Comment} from '../models/comment.model';
import {AppModalAbstractComponent} from '@app/components/modal.component.abstract';

@Component({
    selector: 'app-modal-comment',
    templateUrl: './modal-comment.component.html',
    styles: []
})
export class ModalCommentComponent extends AppModalAbstractComponent<Comment> implements OnInit, OnDestroy {

    model = new Comment(0, '');
    baseUrl = '';
    form = new FormGroup({
        id: new FormControl('', []),
        threadId: new FormControl('', [Validators.required]),
        vote: new FormControl('', [Validators.required]),
        status: new FormControl('', [Validators.required]),
        comment: new FormControl('', [Validators.required]),
        reply: new FormControl('', []),
    });
    statusesOpts: {name: string, title: string}[] = [];

    constructor(
        public ref: DynamicDialogRef,
        public config: DynamicDialogConfig,
        public systemNameService: SystemNameService,
        public dataService: CommentsService,
        private appSettings: AppSettings,
        private translateService: TranslateService,
    ) {
        super(ref, config, systemNameService, dataService);
        this.baseUrl = `${this.appSettings.settings.webApiUrl}/`;
    }

    ngOnInit(): void {
        this.statusesOpts.push({
            name: 'pending',
            title: this.getLangString('PENDING')
        });
        this.statusesOpts.push({
            name: 'published',
            title: this.getLangString('PUBLISHED')
        });
        this.statusesOpts.push({
            name: 'deleted',
            title: this.getLangString('DELETED')
        });
        super.ngOnInit();
    }

    getLangString(value: string): string {
        if (!this.translateService.store.translations[this.translateService.currentLang]) {
            return value;
        }
        const translations = this.translateService.store.translations[this.translateService.currentLang];
        return translations[value] || value;
    }
}
