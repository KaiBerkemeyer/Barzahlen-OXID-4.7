<?php
/**
 * Barzahlen Payment Module (OXID eShop)
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 of the License
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://www.gnu.org/licenses/
 *
 * @copyright   Copyright (c) 2012 Zerebro Internet GmbH (http://www.barzahlen.de)
 * @author      Alexander Diebler
 * @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

class Unit_Barzahlen_BarzahlenTransactionsTest extends OxidTestCase {

  /**
   * Test loading a order with a pending transaction.
   */
  public function testRenderLoadPending() {

    modConfig::setParameter("oxid", "2a289076590d790c6d50aabd6f5974eb");

    $oView = $this->getProxyClass('barzahlen_transactions');
    $this->assertEquals('barzahlen_transactions.tpl', $oView->render());

    $aViewData = $oView->getNonPublicVar('_aViewData');
    $this->assertEquals('oxidbarzahlen', $aViewData['payment']);
    $this->assertEquals('27767255', $aViewData['transactionId']);
    $this->assertEquals('BZ__STATE_PENDING', $aViewData['state']);
    $this->assertEquals('EUR', $aViewData['currency']);
  }

  /**
   * Test loading a order with a paid transaction.
   */
  public function testRenderLoadPaid() {

    modConfig::setParameter("oxid", "c07fabf21fc080a3d2f81d951a405c37");

    $oView = $this->getProxyClass('barzahlen_transactions');
    $this->assertEquals('barzahlen_transactions.tpl', $oView->render());

    $aViewData = $oView->getNonPublicVar('_aViewData');
    $this->assertEquals('oxidbarzahlen', $aViewData['payment']);
    $this->assertEquals('27767342', $aViewData['transactionId']);
    $this->assertEquals('BZ__STATE_PAID', $aViewData['state']);
    $this->assertEquals('EUR', $aViewData['currency']);
    $this->assertEquals(25.9, $aViewData['refundable']);
  }

  /**
   * Test loading a order with a expired transaction.
   */
  public function testRenderLoadExpired() {

    modConfig::setParameter("oxid", "0dae2566880a9e512886c0a7cd63dd9a");

    $oView = $this->getProxyClass('barzahlen_transactions');
    $this->assertEquals('barzahlen_transactions.tpl', $oView->render());

    $aViewData = $oView->getNonPublicVar('_aViewData');
    $this->assertEquals('oxidbarzahlen', $aViewData['payment']);
    $this->assertEquals('27767428', $aViewData['transactionId']);
    $this->assertEquals('BZ__STATE_EXPIRED', $aViewData['state']);
    $this->assertEquals('EUR', $aViewData['currency']);
  }

  /**
   * Test loading a order with a paid transaction.
   * One pending refund.
   */
  public function testRenderLoadRefundPending() {

    modConfig::setParameter("oxid", "de4576783559ce1477e46db9af4f44bb");

    $oView = $this->getProxyClass('barzahlen_transactions');
    $this->assertEquals('barzahlen_transactions.tpl', $oView->render());

    $aViewData = $oView->getNonPublicVar('_aViewData');
    $this->assertEquals('oxidbarzahlen', $aViewData['payment']);
    $this->assertEquals('27767507', $aViewData['transactionId']);
    $this->assertEquals('BZ__STATE_PAID', $aViewData['state']);
    $this->assertEquals('EUR', $aViewData['currency']);
    $this->assertEquals(22, $aViewData['refundable']);

    $aRefundData = $aViewData['refunds'];
    $this->assertEquals(27828537, $aRefundData[0]['refundid']);
    $this->assertEquals(3.9, $aRefundData[0]['amount']);
    $this->assertEquals('BZ__STATE_PENDING', $aRefundData[0]['state']);
  }

  /**
   * Test loading a order with a paid transaction.
   * One pending, one completed refund.
   */
  public function testRenderLoadRefundsPendingCompleted() {

    modConfig::setParameter("oxid", "6988a7466abe756b93c1f0b2b11af7d3");

    $oView = $this->getProxyClass('barzahlen_transactions');
    $this->assertEquals('barzahlen_transactions.tpl', $oView->render());

    $aViewData = $oView->getNonPublicVar('_aViewData');
    $this->assertEquals('oxidbarzahlen', $aViewData['payment']);
    $this->assertEquals('27767585', $aViewData['transactionId']);
    $this->assertEquals('BZ__STATE_PAID', $aViewData['state']);
    $this->assertEquals('EUR', $aViewData['currency']);
    $this->assertEquals(0, $aViewData['refundable']);

    $aRefundData = $aViewData['refunds'];
    $this->assertEquals(27828393, $aRefundData[0]['refundid']);
    $this->assertEquals(22, $aRefundData[0]['amount']);
    $this->assertEquals('BZ__STATE_PENDING', $aRefundData[0]['state']);
    $this->assertEquals(27828461, $aRefundData[1]['refundid']);
    $this->assertEquals(3.9, $aRefundData[1]['amount']);
    $this->assertEquals('BZ__STATE_COMPLETED', $aRefundData[1]['state']);
  }

  /**
   * Test loading a order with a paid transaction.
   * Two completed refunds.
   */
  public function testRenderLoadRefundsCompleted() {

    modConfig::setParameter("oxid", "a6f9bc61ce7aec5dabb7600636f5ce1d");

    $oView = $this->getProxyClass('barzahlen_transactions');
    $this->assertEquals('barzahlen_transactions.tpl', $oView->render());

    $aViewData = $oView->getNonPublicVar('_aViewData');
    $this->assertEquals('oxidbarzahlen', $aViewData['payment']);
    $this->assertEquals('27767667', $aViewData['transactionId']);
    $this->assertEquals('BZ__STATE_PAID', $aViewData['state']);
    $this->assertEquals('EUR', $aViewData['currency']);
    $this->assertEquals(0, $aViewData['refundable']);

    $aRefundData = $aViewData['refunds'];
    $this->assertEquals(27828255, $aRefundData[0]['refundid']);
    $this->assertEquals(22, $aRefundData[0]['amount']);
    $this->assertEquals('BZ__STATE_COMPLETED', $aRefundData[0]['state']);
    $this->assertEquals(27828321, $aRefundData[1]['refundid']);
    $this->assertEquals(3.9, $aRefundData[1]['amount']);
    $this->assertEquals('BZ__STATE_COMPLETED', $aRefundData[1]['state']);
  }

  /**
   * Tries to get and edit object with selected sOXID.
   */
  public function testGetEditObject() {

    modConfig::setParameter("oxid", "2a289076590d790c6d50aabd6f5974eb");

    $oView = new barzahlen_transactions;
    $oObject = $oView->getEditObject();

    $this->assertEquals('27767255', $oObject->oxorder__bztransaction->rawValue);
    $this->assertEquals('pending', $oObject->oxorder__bzstate->rawValue);
  }

  /**
   * Tries to get and edit object without selected sOXID.
   */
  public function testGetEditObjectNoParameter() {

    $oView = new barzahlen_transactions;
    $oObject = $oView->getEditObject();

    $this->assertEquals(null, $oObject->oxorder__bztransaction->rawValue);
    $this->assertEquals(null, $oObject->oxorder__bzstate->rawValue);
  }

  /**
   * Testing refundable amount for partly refunded transaction.
   */
  public function testGetRefundableOnePendingRefund() {

    modConfig::setParameter("oxid", "de4576783559ce1477e46db9af4f44bb");

    $oView = $this->getProxyClass('barzahlen_transactions');
    $this->assertEquals(22, $oView->_getRefundable());
  }

  /**
   * Testing refundable amount for complete refunded transaction.
   */
  public function testGetRefundableFullRefunded() {

    modConfig::setParameter("oxid", "a6f9bc61ce7aec5dabb7600636f5ce1d");

    $oView = $this->getProxyClass('barzahlen_transactions');
    $this->assertEquals(0, $oView->_getRefundable());
  }

  /**
   * Testing payment slip resending with a success response.
   */
  public function testResendPaymentSlipSuccess() {

    modConfig::setParameter("oxid", "2a289076590d790c6d50aabd6f5974eb");

    $oView = $this->getMock('barzahlen_transactions', array('_connectBarzahlenApi'));
    $oView->expects($this->once())
           ->method('_connectBarzahlenApi')
           ->will($this->returnValue(new successRq));

    $oView->resendPaymentSlip();
  }

  /**
   * Testing payment slip resending with a failure response.
   */
  public function testResendPaymentSlipFailure() {

    modConfig::setParameter("oxid", "c07fabf21fc080a3d2f81d951a405c37");

    $oView = $this->getMock('barzahlen_transactions', array('_connectBarzahlenApi'));
    $oView->expects($this->once())
           ->method('_connectBarzahlenApi')
           ->will($this->returnValue(new failureRq));

    $oView->resendPaymentSlip();
  }

  /**
   * Testing refund slip resending with a success response.
   */
  public function testResendRefundSlipSuccess() {

    modConfig::setParameter("oxid", "6988a7466abe756b93c1f0b2b11af7d3");
    $_POST['refundId'] = '27828393';

    $oView = $this->getMock('barzahlen_transactions', array('_connectBarzahlenApi'));
    $oView->expects($this->once())
           ->method('_connectBarzahlenApi')
           ->will($this->returnValue(new successRq));

    $oView->resendRefundSlip();
  }

  /**
   * Testing refund slip resending with a failure response.
   */
  public function testResendRefundSlipFailure() {

    modConfig::setParameter("oxid", "6988a7466abe756b93c1f0b2b11af7d3");
    $_POST['refundId'] = '27828321';

    $oView = $this->getMock('barzahlen_transactions', array('_connectBarzahlenApi'));
    $oView->expects($this->once())
           ->method('_connectBarzahlenApi')
           ->will($this->returnValue(new failureRq));

    $oView->resendRefundSlip();
  }

  /**
   * Testing refund request with a success response.
   */
  public function testRefundSuccess() {

    modConfig::setParameter("oxid", "c07fabf21fc080a3d2f81d951a405c37");
    $_POST['refund_amount'] = '10';

    $oView = $this->getMock('barzahlen_transactions', array('_connectBarzahlenApi'));
    $oView->expects($this->once())
           ->method('_connectBarzahlenApi')
           ->will($this->returnValue(new successRq));

    $oView->requestRefund();
  }

  /**
   * Testing refund request with a failure response.
   */
  public function testRefundFailure() {

    modConfig::setParameter("oxid", "c07fabf21fc080a3d2f81d951a405c37");
    $_POST['refund_amount'] = '10';

    $oView = $this->getMock('barzahlen_transactions', array('_connectBarzahlenApi'));
    $oView->expects($this->once())
           ->method('_connectBarzahlenApi')
           ->will($this->returnValue(new failureRq));

    $oView->requestRefund();

  }

  /**
   * Testing refund request with a too hight amout.
   */
  public function testRefundTooHighAmount() {

    modConfig::setParameter("oxid", "c07fabf21fc080a3d2f81d951a405c37");
    $_POST['refund_amount'] = '999.99';

    $oView = $this->getMock('barzahlen_transactions', array('_connectBarzahlenApi'));
    $oView->expects($this->never())
           ->method('_connectBarzahlenApi');

    $oView->requestRefund();

  }

  /**
   * Testing the creating of a Barzahlen_Api object.
   */
  public function testGetBarzahlenApi() {

    modConfig::setParameter("oxid", "a6f9bc61ce7aec5dabb7600636f5ce1d");

    $oView = $this->getProxyClass('barzahlen_transactions');
    $oApi = $oView->_getBarzahlenApi();

    $this->assertAttributeEquals(SHOPID, '_shopId', $oApi);
    $this->assertAttributeEquals(PAYMENTKEY, '_paymentKey', $oApi);
    $this->assertAttributeEquals('de', '_language', $oApi);
    $this->assertAttributeEquals(true, '_sandbox', $oApi);
    $this->assertAttributeEquals(0, '_madeAttempts', $oApi);
  }

  /**
   * Removes all temporary data after the currenct test.
   */
  public function tearDown() {
    parent::tearDown();
    unset($_SESSION['headerCode']);
  }
}
?>