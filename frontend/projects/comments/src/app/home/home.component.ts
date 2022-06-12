import {Component, OnDestroy, OnInit} from '@angular/core';

import {TranslateService} from '@ngx-translate/core';
import {ConfirmationService, MessageService} from 'primeng/api';
import {DialogService} from 'primeng/dynamicdialog';

import {QueryOptions} from '@app/models/query-options';
import {CommentsService} from '../services/comments.service';
import {ModalCommentComponent} from './modal-comment.component';
import {AppTablePageAbstractComponent} from '@app/components/table-page.components.abstract';
import {ContentTypesService} from '@app/catalog/services/content_types.service';
import {Comment} from '../models/comment.model';
import {TableField} from '@app/components/table-page.components.abstract';

@Component({
    selector: 'app-home',
    templateUrl: './home.component.html',
    providers: [DialogService, CommentsService]
})
export class HomeComponent extends AppTablePageAbstractComponent<Comment> implements OnInit, OnDestroy {

    queryOptions: QueryOptions = new QueryOptions(1, 12, 'id', 'desc');
    items: Comment[] = [];
    cols: TableField[] = [
        { field: 'id', header: 'ID', outputType: 'text-center', outputProperties: {} },
        { field: 'vote', header: 'VOTE', outputType: 'text', outputProperties: {} },
        { field: 'status', header: 'STATUS', outputType: 'text', outputProperties: {} },
        { field: 'publishedTime', header: 'PUBLISHED_TIME', outputType: 'date', outputProperties: {format: 'dd/MM/y HH:mm:ss'} },
        { field: 'isActive', header: 'STATUS', outputType: 'boolean', outputProperties: {} }
    ];

    constructor(
        public dialogService: DialogService,
        public contentTypesService: ContentTypesService,
        public dataService: CommentsService,
        public translateService: TranslateService,
        public messageService: MessageService,
        public confirmationService: ConfirmationService
    ) {
        super(dialogService, contentTypesService, dataService, translateService, messageService, confirmationService);
    }

    ngOnInit() {
        super.ngOnInit();
        this.menuItems.push({
            label: this.getLangString('DISABLE_ENABLE'),
            icon: 'pi pi-times-circle',
            command: () => {
                this.blockSelected();
            }
        });
    }

    getModalComponent() {
        return ModalCommentComponent;
    }
}
