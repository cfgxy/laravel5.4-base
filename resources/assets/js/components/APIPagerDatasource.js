export class APIPagerDatasource
{
    /**
     * 获取表格标题栏和数据，表格类的构造方法
     * @param url
     * @param columns
     */
    constructor(url, columns) {
        this.rowCount = 0;
        this.columnDefs = columns;
        this.url = url;
        this.params = {};
        this.currentAjaxData = {};

        this.ajaxDataCallback = (data) => data;
        this.dataCallback = (ret) => ret;
        this.successCallback = () => {};
        this.failCallback = () => {};
        this.alwaysCallback = () => {};
    }

    /**
     * 获取表格标题栏
     * @returns {*|Array}
     */
    get columns() {
        return this.columnDefs || [];
    }

    /**
     * 搜索字段
     * @param params
     * @returns {APIPagerDatasource}
     */
    options(params) {
        this.params = params;
        return this;
    }

    /**
     * 表格对象
     * @param grid
     * @returns {APIPagerDatasource}
     */
    grid(grid) {
        this.gridObj = grid;
        return this;
    }

    ajaxData(callback) {
        this.ajaxDataCallback = callback;
        return this;
    }

    data(callback) {
        this.dataCallback = callback;
        return this;
    }

    success(callback) {
        this.successCallback = callback;
        return this;
    }

    fail(callback) {
        this.failCallback = callback;
        return this;
    }

    always(callback) {
        this.alwaysCallback = callback;
        return this;
    }

    /**
     * 获取表格数据
     * @param params
     */
    getRows(params) {

        if (this.gridObj) {
            this.gridObj.changeOverlayLoadingText(this.params.loadingText || '数据加载中...');
        }

        //最后一条数据
        let lastRow = -1;
        if (this.rowCount <= params.endRow) {
            lastRow = this.rowCount;
        }

        let data = DS_Params(params);
        data = this.ajaxDataCallback(data);

        if (data === false) {
            params.failCallback();
            return;
        }

        this.currentAjaxData = data;

        axios.get(this.url, {params: data}).then((ret) => {
            let msg = ret.data;

            if (msg.code === 0) {
                if (this.gridObj) {
                    this.gridObj.api.deselectAll();
                }

                //处理回调数据，并把数据传入successCallback
                msg = this.dataCallback(msg);
                this.rowCount = msg.data.total;
                this.noRowsText = msg.data.extras.empty_text;
                this.successCallback(msg);
                params.successCallback(msg.data.data, msg.data.total);

                if (this.gridObj) {
                    this.gridObj.$emit('ds.changed');
                    this.gridObj.changeOverlayNoRowsText(msg.data.extras.empty_msg || this.params.noRowsText || '暂无数据');
                }
            } else {
                params.failCallback();
                this.failCallback(msg);

                if (this.gridObj) {
                    this.gridObj.changeOverlayNoRowsText(msg.msg || this.params.noRowsText || '暂无数据');
                }
            }

            this.alwaysCallback(msg);
        });
    }
}