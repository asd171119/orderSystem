<script src="/js/common/modal.js"></script>
<link rel="stylesheet" href="/css/common/modal.css">

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="post" action="">

				{!! csrf_field() !!}

				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body"></div>

				<div class="modal-footer">
					<button type="button" name="cancel" class="secondary-btn" data-dismiss="modal">取消</button>
					<button type="button" name="confirm" class="">確認</button>
				</div>

			</form>
		</div>
	</div>
</div>
