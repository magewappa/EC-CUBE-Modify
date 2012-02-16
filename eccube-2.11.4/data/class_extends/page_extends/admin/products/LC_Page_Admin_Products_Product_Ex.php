<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

// {{{ requires
require_once CLASS_REALDIR . 'pages/admin/products/LC_Page_Admin_Products_Product.php';

/**
 * 商品登録 のページクラス(拡張).
 *
 * LC_Page_Admin_Products_Product をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Products_Product_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
 */
class LC_Page_Admin_Products_Product_Ex extends LC_Page_Admin_Products_Product {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        parent::process();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
     * 表示用フォームパラメーター取得
     * - 入力画面
     *
     * @param object $objUpFile SC_UploadFileインスタンス
     * @param object $objDownFile SC_UploadFileインスタンス
     * @param array $arrForm フォーム入力パラメーター配列
     * @return array 表示用フォームパラメーター配列
     */
    function lfSetViewParam_InputPage(&$objUpFile, &$objDownFile, &$arrForm) {
    	parent::lfSetViewParam_InputPage($objUpFile, $objDownFile, $arrForm);
    	
        // 在庫タイプ取得
        if($arrForm['view_stock_type_id'] == "") {
            $arrForm['view_stock_type_id'] = DEFAULT_VIEW_STOCK_TYPE;
        }
        return $arrForm;
    }
    
        /**
     * パラメーター情報の初期化
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @param array $arrPost $_POSTデータ
     * @return void
     */
    function lfInitFormParam(&$objFormParam, $arrPost) {
        $objFormParam->addParam("商品ID", "product_id", INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("商品名", 'name', STEXT_LEN, 'KVa', array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("商品カテゴリ", "category_id", INT_LEN, 'n', array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("公開・非公開", 'status', INT_LEN, 'n', array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("商品ステータス", "product_status", INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));

        if($this->lfGetProductClassFlag($arrPost['has_product_class']) == false) {
            // 新規登録, 規格なし商品の編集の場合
            $objFormParam->addParam("商品種別", "product_type_id", INT_LEN, 'n', array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("ダウンロード商品ファイル名", "down_filename", STEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("ダウンロード商品実ファイル名", "down_realfilename", MTEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("temp_down_file", "temp_down_file", '', "", array());
            $objFormParam->addParam("save_down_file", "save_down_file", '', "", array());
            $objFormParam->addParam("商品コード", "product_code", STEXT_LEN, 'KVna', array("EXIST_CHECK", "SPTAB_CHECK","MAX_LENGTH_CHECK"));
            $objFormParam->addParam(NORMAL_PRICE_TITLE, "price01", PRICE_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam(SALE_PRICE_TITLE, "price02", PRICE_LEN, 'n', array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("在庫数", 'stock', AMOUNT_LEN, 'n', array("SPTAB_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("在庫無制限", "stock_unlimited", INT_LEN, 'n', array("SPTAB_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("在庫タイプ", "view_stock_type_id", INT_LEN, 'n', array("EXIST_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        }
        $objFormParam->addParam("商品送料", "deliv_fee", PRICE_LEN, 'n', array("NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("ポイント付与率", "point_rate", PERCENTAGE_LEN, 'n', array("EXIST_CHECK", "NUM_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("発送日目安", "deliv_date_id", INT_LEN, 'n', array("NUM_CHECK"));
        $objFormParam->addParam("購入制限", "sale_limit", AMOUNT_LEN, 'n', array("SPTAB_CHECK", "ZERO_CHECK", "NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("メーカー", "maker_id", INT_LEN, 'n', array("NUM_CHECK"));
        $objFormParam->addParam("メーカーURL", "comment1", URL_LEN, 'a', array("SPTAB_CHECK", "URL_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("検索ワード", "comment3", LLTEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("備考欄(SHOP専用)", 'note', LLTEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("一覧-メインコメント", "main_list_comment", MTEXT_LEN, 'KVa', array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("詳細-メインコメント", "main_comment", LLTEXT_LEN, 'KVa', array("EXIST_CHECK", "SPTAB_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("save_main_list_image", "save_main_list_image", '', "", array());
        $objFormParam->addParam("save_main_image", "save_main_image", '', "", array());
        $objFormParam->addParam("save_main_large_image", "save_main_large_image", '', "", array());
        $objFormParam->addParam("temp_main_list_image", "temp_main_list_image", '', "", array());
        $objFormParam->addParam("temp_main_image", "temp_main_image", '', "", array());
        $objFormParam->addParam("temp_main_large_image", "temp_main_large_image", '', "", array());

        for ($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $objFormParam->addParam("詳細-サブタイトル" . $cnt, "sub_title" . $cnt, STEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("詳細-サブコメント" . $cnt, "sub_comment" . $cnt, LLTEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("save_sub_image" . $cnt, "save_sub_image" . $cnt, '', "", array());
            $objFormParam->addParam("save_sub_large_image" . $cnt, "save_sub_large_image" . $cnt, '', "", array());
            $objFormParam->addParam("temp_sub_image" . $cnt, "temp_sub_image" . $cnt, '', "", array());
            $objFormParam->addParam("temp_sub_large_image" . $cnt, "temp_sub_large_image" . $cnt, '', "", array());
        }

        for ($cnt = 1; $cnt <= RECOMMEND_PRODUCT_MAX; $cnt++) {
            $objFormParam->addParam("関連商品コメント" . $cnt, "recommend_comment" . $cnt, LTEXT_LEN, 'KVa', array("SPTAB_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("関連商品ID" . $cnt, "recommend_id" . $cnt, INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
            $objFormParam->addParam("recommend_delete" . $cnt, "recommend_delete" . $cnt, '', 'n', array());
        }

        $objFormParam->addParam("商品ID", "copy_product_id", INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));

        $objFormParam->addParam("has_product_class", "has_product_class", INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));
        $objFormParam->addParam("product_class_id", "product_class_id", INT_LEN, 'n', array("NUM_CHECK", "MAX_LENGTH_CHECK"));

        $objFormParam->setParam($arrPost);
        $objFormParam->convParam();

    }
    /**
     * DBに商品データを登録する
     * 
     * @param object $objUpFile SC_UploadFileインスタンス
     * @param object $objDownFile SC_UploadFileインスタンス
     * @param array $arrList フォーム入力パラメーター配列
     * @return integer 登録商品ID
     */
    function lfRegistProduct(&$objUpFile, &$objDownFile, $arrList) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objDb = new SC_Helper_DB_Ex();

        // 配列の添字を定義
        $checkArray = array('name', 'status',
                            "main_list_comment", "main_comment",
                            "deliv_fee", "comment1", "comment2", "comment3",
                            "comment4", "comment5", "comment6", "main_list_comment",
                            "sale_limit", "deliv_date_id", "maker_id", 'note');
        $arrList = SC_Utils_Ex::arrayDefineIndexes($arrList, $checkArray);

        // INSERTする値を作成する。
        $sqlval['name'] = $arrList['name'];
        $sqlval['status'] = $arrList['status'];
        $sqlval['main_list_comment'] = $arrList['main_list_comment'];
        $sqlval['main_comment'] = $arrList['main_comment'];
        $sqlval['comment1'] = $arrList['comment1'];
        $sqlval['comment2'] = $arrList['comment2'];
        $sqlval['comment3'] = $arrList['comment3'];
        $sqlval['comment4'] = $arrList['comment4'];
        $sqlval['comment5'] = $arrList['comment5'];
        $sqlval['comment6'] = $arrList['comment6'];
        $sqlval['main_list_comment'] = $arrList['main_list_comment'];
        $sqlval['deliv_date_id'] = $arrList['deliv_date_id'];
        $sqlval['maker_id'] = $arrList['maker_id'];
        $sqlval['note'] = $arrList['note'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $sqlval['creator_id'] = $_SESSION['member_id'];
        $arrRet = $objUpFile->getDBFileList();
        $sqlval = array_merge($sqlval, $arrRet);

        for($cnt = 1; $cnt <= PRODUCTSUB_MAX; $cnt++) {
            $sqlval['sub_title'.$cnt] = $arrList['sub_title'.$cnt];
            $sqlval['sub_comment'.$cnt] = $arrList['sub_comment'.$cnt];
        }

        $objQuery->begin();

        // 新規登録(複製時を含む)
        if($arrList['product_id'] == "") {
            $product_id = $objQuery->nextVal("dtb_products_product_id");
            $sqlval['product_id'] = $product_id;

            // INSERTの実行
            $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
            $objQuery->insert("dtb_products", $sqlval);

            $arrList['product_id'] = $product_id;

            // カテゴリを更新
            $objDb->updateProductCategories($arrList['category_id'], $product_id);

            // 複製商品の場合には規格も複製する
            if($arrList["copy_product_id"] != "" && SC_Utils_Ex::sfIsInt($arrList["copy_product_id"])) {
                if($this->lfGetProductClassFlag($arrList["has_product_class"]) == false) {
                    //規格なしの場合、複製は価格等の入力が発生しているため、その内容で追加登録を行う
                    $this->lfCopyProductClass($arrList, $objQuery);
                } else {
                    //規格がある場合の複製は複製元の内容で追加登録を行う
                    // dtb_products_class のカラムを取得
                    $dbFactory = SC_DB_DBFactory_Ex::getInstance();
                    $arrColList = $objQuery->listTableFields('dtb_products_class');
                    $arrColList_tmp = array_flip($arrColList);

                    // 複製しない列
                    unset($arrColList[$arrColList_tmp["product_class_id"]]);     //規格ID
                    unset($arrColList[$arrColList_tmp["product_id"]]);           //商品ID
                    unset($arrColList[$arrColList_tmp["create_date"]]);

                    // 複製元商品の規格データ取得
                    $col = SC_Utils_Ex::sfGetCommaList($arrColList);
                    $table = 'dtb_products_class';
                    $where = 'product_id = ?';
                    $objQuery->setOrder('product_class_id');
                    $arrProductsClass = $objQuery->select($col, $table, $where, array($arrList["copy_product_id"]));

                    // 複製元商品の規格組み合わせデータ登録
                    // 登録した組み合わせIDを取得
                    $arrRetCombinationId = $this->lfRegistClassCombination($arrProductsClass);

                    // 規格データ登録
                    $objQuery =& SC_Query_Ex::getSingletonInstance();
                    foreach($arrProductsClass as $arrData) {
                        $sqlval = array();
                        $sqlval['product_class_id'] = $objQuery->nextVal('dtb_products_class_product_class_id');
                        $sqlval['product_id'] = $product_id;
                        $sqlval['create_date'] = 'CURRENT_TIMESTAMP';
                        $sqlval['class_combination_id'] = $arrRetCombinationId[$arrData['class_combination_id']];
                        $sqlval['product_type_id'] = $arrData['product_type_id'];
                        $sqlval['product_code'] = $arrData['product_code'];
                        $sqlval['stock'] = $arrData['stock'];
                        $sqlval['stock_unlimited'] = $arrData['stock_unlimited'];
                        $sqlval['sale_limit'] = $arrData['sale_limit'];
                        $sqlval['view_stock_type_id'] = $arrData['view_stock_type_id'];
                        $sqlval['price01'] = $arrData['price01'];
                        $sqlval['price02'] = $arrData['price02'];
                        $sqlval['deliv_fee'] = $arrData['deliv_fee'];
                        $sqlval['point_rate'] = $arrData['point_rate'];
                        $sqlval['creator_id'] = $arrData['creator_id'];
                        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
                        $sqlval['down_filename'] = $arrData['down_filename'];
                        $sqlval['down_realfilename'] = $arrData['down_realfilename'];
                        $sqlval['del_flg'] = $arrData['del_flg'];
                        $objQuery->insert($table, $sqlval);
                    }
                }
            }
        // 更新
        } else {
            $product_id = $arrList['product_id'];
            // 削除要求のあった既存ファイルの削除
            $arrRet = $this->lfGetProductData_FromDB($arrList['product_id']);
            // TODO: SC_UploadFile::deleteDBFileの画像削除条件見直し要
            $objImage = new SC_Image_Ex($objUpFile->temp_dir);
            $arrKeyName = $objUpFile->keyname;
            $arrSaveFile = $objUpFile->save_file;
            $arrImageKey = array();
            foreach ($arrKeyName as $key => $keyname) {
                if ($arrRet[$keyname] && !$arrSaveFile[$key]) {
                    $arrImageKey[] = $keyname;
                    $has_same_image = $this->lfHasSameProductImage($arrList['product_id'], $arrImageKey, $arrRet[$keyname]);
                    if (!$has_same_image) {
                        $objImage->deleteImage($arrRet[$keyname], $objUpFile->save_dir);
                    }
                }
            }
            $objDownFile->deleteDBDownFile($arrRet);
            // UPDATEの実行
            $where = "product_id = ?";
            $objQuery->update("dtb_products", $sqlval, $where, array($product_id));

            // カテゴリを更新
            $objDb->updateProductCategories($arrList['category_id'], $product_id);
        }

        // 商品登録の時は規格を生成する。複製の場合は規格も複製されるのでこの処理は不要。
        if($arrList["copy_product_id"] == "") {
            // 規格登録
            if ($objDb->sfHasProductClass($product_id)) {
                // 規格あり商品（商品規格テーブルのうち、商品登録フォームで設定するパラメーターのみ更新）
                $this->lfUpdateProductClass($arrList);
            } else {
                // 規格なし商品（商品規格テーブルの更新）
                $this->lfInsertDummyProductClass($arrList);
            }
        }

        // 商品ステータス設定
        $objProduct = new SC_Product_Ex();
        $objProduct->setProductStatus($product_id, $arrList['product_status']);

        // 関連商品登録
        $this->lfInsertRecommendProducts($objQuery, $arrList, $product_id);

        $objQuery->commit();
        return $product_id;
    }
}
?>
